<?php

namespace App\Services\Media;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Assainit un SVG par liste blanche : seuls les éléments graphiques courants sont conservés.
 * Scripts, gestionnaires d'événements (onclick…), objets externes et liens vers l'extérieur sont supprimés.
 */
class SvgSanitizer
{
    private const ALLOWED_ELEMENTS = [
        'svg', 'g', 'path', 'circle', 'ellipse', 'rect', 'line', 'polyline', 'polygon', 'text', 'tspan', 'textpath',
        'defs', 'lineargradient', 'radialgradient', 'stop', 'clippath', 'mask', 'pattern', 'symbol', 'use',
        'title', 'desc', 'filter', 'marker', 'image',
    ];

    public function sanitize(string $svg): ?string
    {
        // Pas de DOCTYPE : c'est le vecteur des attaques par entités externes.
        $svg = preg_replace('/<!DOCTYPE[^>]*(\[.*?\])?\s*>/is', '', $svg);

        $previous = libxml_use_internal_errors(true);
        $doc = new DOMDocument;
        $loaded = $doc->loadXML((string) $svg, LIBXML_NONET | LIBXML_NOBLANKS);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded || ! $doc->documentElement || strtolower($doc->documentElement->localName) !== 'svg') {
            return null;
        }

        $this->clean($doc->documentElement);

        return $doc->saveXML($doc->documentElement);
    }

    private function clean(DOMElement $element): void
    {
        $this->cleanAttributes($element);

        foreach (iterator_to_array($element->childNodes) as $child) {
            $this->cleanNode($child);
        }
    }

    private function cleanNode(DOMNode $node): void
    {
        if ($node instanceof DOMElement) {
            $name = strtolower($node->localName);

            if (! in_array($name, self::ALLOWED_ELEMENTS, true) && ! str_starts_with($name, 'fe')) {
                $node->parentNode?->removeChild($node);

                return;
            }

            $this->clean($node);
        } elseif ($node->nodeType !== XML_TEXT_NODE && $node->nodeType !== XML_CDATA_SECTION_NODE) {
            // Commentaires, instructions de traitement, etc.
            $node->parentNode?->removeChild($node);
        }
    }

    private function cleanAttributes(DOMElement $element): void
    {
        foreach (iterator_to_array($element->attributes) as $attribute) {
            $name = strtolower($attribute->nodeName);
            $value = trim($attribute->nodeValue);

            $dangerous = str_starts_with($name, 'on')
                || (in_array($name, ['href', 'xlink:href'], true) && ! $this->isSafeReference($value))
                || ($name === 'style' && preg_match('/url\s*\(|expression|javascript:|@import/i', $value));

            if ($dangerous) {
                $element->removeAttributeNode($attribute);
            }
        }
    }

    /** Seules les références internes (#id) et les images embarquées en data: sont acceptées. */
    private function isSafeReference(string $value): bool
    {
        return str_starts_with($value, '#') || (bool) preg_match('#^data:image/(png|jpeg|gif|webp);base64,#i', $value);
    }
}
