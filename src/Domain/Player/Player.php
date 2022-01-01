<?php

namespace Guess\Domain\Player;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Guess\Domain\Game\Game;
use RuntimeException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Player implements UserInterface, PasswordAuthenticatedUserInterface
{
    const RIGHT_GUESS_POINT = 1;

    private int $id;
    private string $username;
    private string $password;
    private string $email;
    private DateTimeInterface $createdAt;
    private int $point;
    private int $avatar;
    private bool $isActive;
    private Collection $guesses;

    public function __construct()
    {
        $this->avatar = 1;
        $this->point = 0;
        $this->createdAt = new DateTimeImmutable();
        $this->isActive = true;
        $this->guesses = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return DateTimeImmutable|DateTimeInterface
     */
    public function getCreatedAt(): DateTimeImmutable|DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable|DateTimeInterface $createdAt
     */
    public function setCreatedAt(DateTimeImmutable|DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getPoint(): int
    {
        return $this->point;
    }

    /**
     * @param int $point
     */
    public function setPoint(int $point): void
    {
        $this->point = $point;
    }

    /**
     * @return int
     */
    public function getAvatar(): int
    {
        return $this->avatar;
    }

    /**
     * @param int $avatar
     */
    public function setAvatar(int $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return ArrayCollection
     */
    public function getGuesses(): ArrayCollection
    {
        return $this->guesses;
    }

    /**
     * @param ArrayCollection $guesses
     */
    public function setGuesses(ArrayCollection $guesses): void
    {
        $this->guesses = $guesses;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }


    public function getRoles(): array
    {
//        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function makeGuesses(Game $game, int $homeTeamGuess, int $awayTeamGuess): void
    {
        if ((new DateTimeImmutable()) > $game->getGameTime()) {
            throw new RuntimeException('Starting time passed for this game, cant make guess');
        }

        $guess = new Guess();
        $guess->setPlayer($this);
        $guess->setGame($game);
        $guess->setCreatedAt(new DateTimeImmutable());
        $guess->setGuess($homeTeamGuess . '-' . $awayTeamGuess);

        $this->guesses->add($guess);
        $game->addGuess($guess);
    }

    public function pointUp(): void
    {
        $this->point += self::RIGHT_GUESS_POINT;
    }
}
