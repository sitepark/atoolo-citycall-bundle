<?php

declare(strict_types=1);

namespace Atoolo\CityCall\Service\Indexer\Enricher\SiteKitSchema2x;

use Atoolo\Resource\Resource;
use Atoolo\Search\Exception\DocumentEnrichingException;
use Atoolo\Search\Service\Indexer\DocumentEnricher;
use Atoolo\Search\Service\Indexer\IndexDocument;
use Atoolo\Search\Service\Indexer\IndexSchema2xDocument;

/**
 * @implements DocumentEnricher<IndexSchema2xDocument>
 */
class NewsDocumentEnricher implements DocumentEnricher
{
    public function isIndexable(Resource $resource): bool
    {
        return true;
    }

    /**
     * @throws DocumentEnrichingException
     */
    public function enrichDocument(
        Resource $resource,
        IndexDocument $doc,
        string $processId
    ): IndexDocument {

        if ($resource->getObjectType() !== 'citycall-news') {
            return $doc;
        }

        return $this->enrichDocumentForNews($resource, $doc);
    }

    /**
     * @throws DocumentEnrichingException
     */
    private function enrichDocumentForNews(
        Resource $resource,
        IndexSchema2xDocument $doc
    ): IndexSchema2xDocument {

        $isAdHocActive = $resource->getData()->getBool(
            'metadata.isAdHocActive'
        );
        if ($isAdHocActive) {
            $doc->setMetaBool('citycall_isadhoc', true);
        }

        return $doc;
    }
}
