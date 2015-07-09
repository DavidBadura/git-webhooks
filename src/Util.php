<?php
/**
 *
 */

namespace DavidBadura\GitWebhooks;

use DavidBadura\GitWebhooks\Event\PushEvent;

/**
 * @author David Badura <d.a.badura@gmail.com>
 */
class Util
{
    /**
     * @param string $ref
     * @return string
     */
    public static function getPushType($ref)
    {
        if (strpos($ref, 'refs/tags/') === 0) {
            return PushEvent::TYPE_TAG;
        }

        if (strpos($ref, 'refs/heads/') === 0) {
            return PushEvent::TYPE_BRANCH;
        }

        throw new \InvalidArgumentException("type not supported");
    }

    /**
     * @param string $ref
     * @return string
     */
    public static function getBranchName($ref)
    {
        if (self::getPushType($ref) != PushEvent::TYPE_BRANCH) {
            throw new \InvalidArgumentException("ref isn't a branch");
        }

        return substr($ref, 11);
    }

    /**
     * @param string $ref
     * @return string
     */
    public static function getTagName($ref)
    {
        if (self::getPushType($ref) != PushEvent::TYPE_TAG) {
            throw new \InvalidArgumentException("ref isn't a tag");
        }

        return substr($ref, 10);
    }
}