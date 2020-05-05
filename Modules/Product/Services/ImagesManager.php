<?php

declare(strict_types = 1);

namespace Modules\Product\Services;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Modules\Product\Exceptions\ModelRelationMissingException;

/**
 * Class ImagesManager
 * @package App\Services
 */
class ImagesManager
{
    /**
     *
     */
    const DEFAULT_PATH_PREFIX = 'default';

    /**
     *
     */
    const PATH_PRODUCT = 'products';
    /**
     *
     */
    const PATH_CATEGORY = 'categories';

    /**
     * @param Model $model
     * @param array $files
     * @param string $relatedClass
     * @param string $relatedClassField
     * @param string $pathPrefix
     * @param bool $delete
     * @return iterable
     * @throws ModelRelationMissingException
     */
    public static function saveMany(
        Model $model,
        array $files,
        string $relatedClass,
        string $relatedClassField = 'file',
        string $pathPrefix = self::DEFAULT_PATH_PREFIX,
        bool $delete = false
    ): iterable
    {
        if (!method_exists($model, 'images')) {
            throw ModelRelationMissingException::missingImagesRelation(class_basename($model));
        }

        if ($delete) {
            self::deleteAll($model);
        }

        $pathArray = [];

        foreach ($files as $file) {
            $path = $file->store($pathPrefix . '/' . $model->id);
            $pathArray[] = new $relatedClass([$relatedClassField => $path]);
        }

        return $model->images()->saveMany($pathArray);
    }

    /**
     * @param Model $model
     * @param string $relatedClassField
     * @throws ModelRelationMissingException
     */
    public static function deleteAll(Model $model, string $relatedClassField = 'file'): void
    {
        if (!method_exists($model, 'images')) {
            throw ModelRelationMissingException::missingImagesRelation(class_basename($model));
        }

        Storage::delete(
            $model->images->pluck($relatedClassField)->toArray()
        );

        $model->images()->delete();
    }
}