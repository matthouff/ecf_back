<?php

namespace App\Entity;

use App\Repository\SocieteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ORM\Entity(repositoryClass=SocieteRepository::class)
 * @UniqueEntity("login")
 */
class Societe implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $login;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups ({"read:offres"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"read:offres"})
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups ({"read:offres"})
     */
    private $logo_color;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"read:offres"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"read:offres"})
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups ({"read:offres"})
     */
    private $firstname_contact;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups ({"read:offres"})
     */
    private $lastname_contact;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups ({"read:offres"})
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Groups ({"read:offres"})
     */
    private $mobile_contact;

    /**
     * @ORM\OneToMany(targetEntity=Offres::class, mappedBy="societe", orphanRemoval=true)
     */
    private $offres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getLogoColor(): ?string
    {
        return $this->logo_color;
    }

    public function setLogoColor(?string $logo_color): self
    {
        $this->logo_color = $logo_color;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getFirstnameContact(): ?string
    {
        return $this->firstname_contact;
    }

    public function setFirstnameContact(?string $firstname_contact): self
    {
        $this->firstname_contact = $firstname_contact;

        return $this;
    }

    public function getLastnameContact(): ?string
    {
        return $this->lastname_contact;
    }

    public function setLastnameContact(?string $lastname_contact): self
    {
        $this->lastname_contact = $lastname_contact;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getMobileContact(): ?string
    {
        return $this->mobile_contact;
    }

    public function setMobileContact(?string $mobile_contact): self
    {
        $this->mobile_contact = $mobile_contact;

        return $this;
    }

    /**
     * @return Collection<int, Offres>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offres $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setSociete($this);
        }

        return $this;
    }

    public function removeOffre(Offres $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getSociete() === $this) {
                $offre->setSociete(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

}
