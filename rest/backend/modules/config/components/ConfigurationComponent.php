<?php
/**
 * Author: Ivan Pushkin
 * Email: metal@vintage.com.ua
 */

namespace backend\modules\config\components;

use backend\modules\config\models\Configuration;
use yii\base\Component;

/**
 * Class ConfigurationComponents
 *
 * @package configuration\components
 */
class ConfigurationComponent extends Component
{
    /**
     *
     * @var string
     */
    public $cacheId = 'cache';

    /**
     * Cache expire time
     *
     * @var int
     */
    public $cacheExpire = 2592000; // 30 days

    /**
     * Config items
     *
     * @var array
     */
    protected $_configs = [];

    /**
     * Load not loaded configs
     *
     * @var bool
     */
    public $lazyLoad = true;

    /**
     * Initialize
     */
    public function init()
    {
        parent::init();

        /** @var Configuration[] $configs */
        //TODO ADD PRELOADED SCOPE
        $configs = Configuration::find()->select(array('config_key', 'value', 'type',))->all();
        $this->_configs = $this->generateConfigArray($configs);
    }

    /**
     * Generate config array with type in item array
     *
     * @param Configuration[] $models
     *
     * @return array
     */
    public function generateConfigArray($models)
    {
        $result = [];
        foreach ($models as $model) {
            $result[$model->config_key] = [
                'value' => $model->value,
                'type' => $model->type,
            ];
        }
        return $result;
    }

    /**
     * Get config
     *
     * @param $key
     * @param null|string $default default value if nothing found
     * @param bool $force do not use cache
     *
     * @return int|null|string
     */
    public function get($key, $default = null, $force = false)
    {
        $value = $default;
        if ($force) {
            $config = Configuration::find()
                ->select(['config_key', 'value', 'type'])
                ->where('config_key=:ck', [':ck' => $key])
                ->one();

            $value = $config ? $this->getValue($config, $default) : $default;
        } elseif (isset($this->_configs[$key])) {
            $value = $this->getValue($this->_configs[$key], $default);
        } elseif ($this->lazyLoad) {
            $config = Configuration::find()
                ->select(array('config_key', 'value', 'type',))
                ->where('config_key=:ck', [':ck' => $key])
                ->one();

            if ($config) {
                $this->_configs[$config->config_key] = [
                    'value' => $config->value,
                    'type' => $config->type,
                ];
                $value = $this->getValue($config, $default);
            }
        }
        return $value;
    }

    /**
     * Return value by type
     *
     * @param $array
     * @param null|string $default default value if nothing found
     *
     * @return int|null|string
     */
    protected function getValue($array, $default)
    {
        $value = $default;
        switch ($array['type']) {
            case Configuration::TYPE_STRING:
            case Configuration::TYPE_HTML:
            case Configuration::TYPE_TEXT:
            case Configuration::TYPE_INTEGER:
            $value = $array['value'];
                break;
            case Configuration::TYPE_BOOLEAN:
                $value = (int)$array['value'];
                break;
            case Configuration::TYPE_FLOAT:
                $value = (float)$array['value'];
                break;
//            case Configuration::TYPE_FILE:
//                $value = FPM::originalSrc((int)$array['value']);
//                break;
            case Configuration::TYPE_IMAGE:
                $value = $array['value'];
                break;
        }
        return $value;
    }
}
