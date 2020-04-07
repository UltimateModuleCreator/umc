<?php
declare(strict_types=1);

namespace App\Model;

class SerializedTypeFactory
{
    /**
     * @var string
     */
    private const DEFAULT_TYPE_CLASS = SerializedType::class;
    /**
     * @var array
     */
    private $typeMap;
    /**
     * @var \Twig\Environment;
     */
    private $twig;

    /**
     * AttributeTypeFactory constructor.
     * @param array $typeMap
     */
    public function __construct(\Twig\Environment $twig, array $typeMap)
    {
        $this->twig = $twig;
        $this->typeMap = array_filter(
            $typeMap,
            function ($item) {
                return isset($item['can_be_serialized']) && $item['can_be_serialized'];
            }
        );
    }

    /**
     * @param Serialized $serialized
     * @return SerializedType
     */
    public function create(Serialized $serialized): SerializedType
    {
        $type = $serialized->getType();
        if (!isset($this->typeMap[$type])) {
            throw new \InvalidArgumentException("There is no config for serialized type {$type}");
        }
        $config = $this->typeMap[$type];
        $class = $config['serialized_class'] ?? self::DEFAULT_TYPE_CLASS;
        return new $class($this->twig, $serialized, $config);
    }
}
