<?php
namespace App\Tags;

use App\Models\Conversation;

/**
 * Class GetversionTag
 * @package App\Tags
 */
class GetversionTag extends VersionTag
{
    protected $tagName = "Getversion";

    /**
     * GetversionTag Constructor.
     * @param Conversation $conversation
     * @param $attributes
     */
    public function __construct(Conversation $conversation, $attributes)
    {
        parent::__construct($conversation, $attributes);
    }
}
