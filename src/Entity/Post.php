<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post implements JsonSerializable
{
    public const SHOW = 0;
    public const HIDE = 1;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $excerpt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $seo_keyword;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tags;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="smallint")
     */
    private $state;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSeoKeyword(): ?string
    {
        return $this->seo_keyword;
    }

    public function setSeoKeyword(string $seo_keyword): self
    {
        $this->seo_keyword = $seo_keyword;

        return $this;
    }

    public function getCategories(): ?string
    {
        if ($this->categories !== '' && $this->categories !== null) {
            return implode(', ', json_decode($this->categories));
        }
        return $this->categories;
    }

    public function setCategories(string $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'excerpt'       => $this->excerpt,
            'image'         => $this->image,
            'seo_keyword'   => $this->seo_keyword,
            'categories'    => $this->categories,
            'tags'          => $this->tags,
            'state'         => $this->state,
        ];
    }
}
