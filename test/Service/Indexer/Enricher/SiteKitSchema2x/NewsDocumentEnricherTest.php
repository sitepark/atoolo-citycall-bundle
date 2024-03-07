<?php

declare(strict_types=1);

namespace Atoolo\CityCall\Test\Service\Indexer\Enricher\SiteKitSchema2x;

// phpcs:ignore
use Atoolo\CityCall\Service\Indexer\Enricher\SiteKitSchema2x\NewsDocumentEnricher;
use Atoolo\Resource\Resource;
use Atoolo\Search\Service\Indexer\IndexSchema2xDocument;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

// phpcs:ignore

#[CoversClass(NewsDocumentEnricher::class)]
class NewsDocumentEnricherTest extends TestCase
{
    public function testIsIndexable(): void
    {
        $enricher = new NewsDocumentEnricher();
        $resource = $this->createMock(Resource::class);
        self::assertTrue(
            $enricher->isIndexable($resource),
            "should not mark any resource as not indexable"
        );
    }

    public function testIsAdHocActive(): void
    {
        $doc = $this->enrichDocument(
            'citycall-news',
            [
                'metadata' => [
                    'isAdHocActive' => true
                ]
            ]
        );

        /** @var array{sp_meta_string_leikanumber: bool} $fields */
        $fields = $doc->getFields();

        $this->assertTrue(
            $fields['sp_meta_bool_citycall_isadhoc'],
            'unexpected isadhoc'
        );
    }

    public function testWithNonNewsObjectType(): void
    {
        $doc = $this->enrichDocument(
            'otherType',
            [
                'metadata' => [
                    'isAdHocActive' => true
                ]
            ]
        );

        /** @var array{sp_meta_string_leikanumber: bool} $fields */
        $fields = $doc->getFields();

        $this->assertEmpty(
            $fields,
            'document should be empty'
        );
    }

    private function enrichDocument(
        string $objectType,
        array $data
    ): IndexSchema2xDocument {
        $enricher = new NewsDocumentEnricher();
        $doc = new IndexSchema2xDocument();

        $resource = new Resource(
            'test',
            'test',
            'test',
            $objectType,
            $data
        );

        return $enricher->enrichDocument($resource, $doc, '');
    }
}
