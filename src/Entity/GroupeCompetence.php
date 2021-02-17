<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeCompetenceRepository::class)
 * @UniqueEntity("libelle")
 * @ApiResource(
 *     collectionOperations={
 *          "getAllgroupeCompetence"={
 *               "path"="/admin/grpecompetences" ,
 *                "method"="GET" ,
 *                  "normalization_context"={"groups"={"grpecompetence:read"}} ,
 *          } ,
 *           "getAllgroupeComp_competence"={
 *               "path"="/admin/grpecompetences/competences" ,
 *                "method"="GET" ,
 *                  "normalization_context"={"groups"={"grpecompetenceCompetence:read"}} ,
 *          } ,
 *          "adding"={
 *              "route_name"="addGrpecompetence" ,
 *               "path"="/admin/grpecompetences" ,
 *                "method"="POST" ,
 *                  "normalization_context"={"groups"={"grpecompetence:read"}} ,
 *          }
 *     } ,
 *     itemOperations={
 *          "getGroupecompetenceById"={
 *               "path"="/admin/grpecompetences/{id}" ,
 *                 "method"="GET" ,
 *                    "normalization_context"={"groups"={"getGroupecompetenceById:read"}} ,
 *          } ,
 *           "getGroupecompetence_competenceById"={
 *                  "path"="/admin/grpecompetences/{id}/competences" ,
 *                 "method"="GET" ,
 *                  "normalization_context"={"groups"={"grpecompetenceCompetenceById:read"}} ,
 *          } ,
 *     }
 * )
 */
class GroupeCompetence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"getPromoRefbyId:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grpecompetence:read","grpecompetenceCompetence:read","grpecompetenceCompetenceById:read","postgrpecompetence:read",
     *              "getGroupecompetenceById:read","referentiel:read","referentielCompetence:read","getReferentielById:read",
     *              "getPromorefbyId:read"})
     */
    private $libelle;

    /**
     * @ORM\ManyToMany(targetEntity=Competence::class, mappedBy="grpe_competence")
     * @ApiSubresource()
     * @Groups({"grpecompetenceCompetence:read","grpecompetenceCompetenceById:read","postgrpecompetence:read",
     *     "getGroupecompetenceById:read","referentielCompetence:read","getPromorefbyId:read"})
     */
    private $competences;

    /**
     * @ORM\ManyToMany(targetEntity=Referentiel::class, mappedBy="grpcompetence")
     * @ApiSubresource()
     * @Groups({"grpecompetenceCompetence:read","grpecompetenceCompetenceById:read","postgrpecompetence:read",
     *     "getGroupecompetenceById:read","referentielCompetence:read","getPromorefbyId:read"})
     */
    private $referentiels;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->referentiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
            $competence->addGrpeCompetence($this);
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competences->removeElement($competence)) {
            $competence->removeGrpeCompetence($this);
        }

        return $this;
    }

    /**
     * @return Collection|Referentiel[]
     */
    public function getReferentiels(): Collection
    {
        return $this->referentiels;
    }

    public function addReferentiel(Referentiel $referentiel): self
    {
        if (!$this->referentiels->contains($referentiel)) {
            $this->referentiels[] = $referentiel;
            $referentiel->addGrpcompetence($this);
        }

        return $this;
    }

    public function removeReferentiel(Referentiel $referentiel): self
    {
        if ($this->referentiels->removeElement($referentiel)) {
            $referentiel->removeGrpcompetence($this);
        }

        return $this;
    }
}
