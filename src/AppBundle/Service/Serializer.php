<?php

namespace AppBundle\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SerializerComponent;

/**
 * Class Serializer
 *
 * @package AppBundle\Service
 */
class Serializer
{

    /** @var string */
    const JSON_FORMAT = 'json';

    /**
     * Serializer data to JSON
     *
     * @param $data
     * @return string
     */
    public function toJson($data):string
    {

        $encoders = [new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer()];
        $serializer = new SerializerComponent($normalizers, $encoders);

        return $serializer->serialize($data, self::JSON_FORMAT);
    }

}