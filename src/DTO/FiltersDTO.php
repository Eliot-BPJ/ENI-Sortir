<?php

namespace App\DTO;

use App\Entity\Etats;
use App\Entity\Sites;

class FiltersDTO
{
    public string $search;
    public Sites $sites;
    public Etats $etat;
    public ?\DateTimeInterface $dateDebut = null;
    public ?\DateTimeInterface $dateFin = null;
    public bool $organisateurFilter = true;
    public bool $inscritFilter = true;
    public bool $pasInscritFilter = true;
    public bool $passeFilter = false;
}
