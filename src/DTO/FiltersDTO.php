<?php

namespace App\DTO;

use App\Entity\Etats;
use App\Entity\Sites;

class FiltersDTO
{
    public string $search;
    public Sites $sites;
    public Etats $etat;
    public ?\DateTimeInterface $dateDebut;
    public ?\DateTimeInterface $dateFin;
    public bool $organisateurFilter;
    public bool $inscritFilter;
    public bool $pasInscritFilter;
    public bool $passeFilter;
}
