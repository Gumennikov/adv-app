<?php

namespace App\Console\Commands\Search;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Advert\Value;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;

class InitCommand extends \Illuminate\Console\Command
{
    protected $signature = 'search:init';

    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle():bool
    {
        try {
            $this->client->indices()->delete([
                'index' => 'app'
            ]);
        } catch (Missing404Exception $e) {}

        $this->client->indices()->create([
            'index' => 'app',
            'body' => [ //Если передать пустой массив body[], то ES будет определять тип полей автоматически
                'mappings' => [
                    //'advert' => [
                        '_source' => ['enabled' => true],
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'published_at' => [
                                'type' => 'date',
                            ],
                            'title' => [
                                'type' => 'text',
                            ],
                            'content' => [
                                'type' => 'text',
                            ],
                            'price' => [
                                'type' => 'integer',
                            ],
                            'status' => [
                                'type' => 'keyword',
                            ],
                            'categories' => [
                                'type' => 'integer',
                            ],
                            'regions' => [
                                'type' => 'integer',
                            ],
                            'values' => [
                                'type' => 'nested',
                                'properties' => [
                                    'attribute' => [
                                        'type' => 'integer',
                                    ],
                                    'value_string' => [
                                        'type' => 'keyword',
                                    ],
                                    'value_int' => [
                                        'type' => 'integer',
                                    ],
                                ],
                            ],
                        ],
                    //],
                ],
                'settings' => [
                    'analysis' => [
                        'char_filter' => [
                            'replace' => [
                                'type' => 'mapping',
                                'mappings' => [
                                    '&=> and '
                                ],
                            ],
                        ],
                        'filter' => [
                            'word_delimiter' => [
                                'type' => 'word_delimiter',
                                'split_on_numerics' => false,
                                'split_on_case_change' => true,
                                'generate_word_parts' => true,
                                'generate_number_parts' => true,
                                'catenate_all' => true,
                                'preserve_original' => true,
                                'catenate_numbers' => true,
                            ],
                            'trigrams' => [
                                'type' => 'ngram',
                                'min_gram' => 3,
                                'max_gram' => 3,
                            ],
                        ],
                        'analyzer' => [
                            'default' => [
                                'type' => 'custom',
                                'char_filter' => [
                                    'html_strip',
                                    'replace',
                                ],
                                'tokenizer' => 'whitespace',
                                'filter' => [
                                    'lowercase',
                                    'word_delimiter',
                                    'trigrams',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        return true;
    }
}
