<?php

namespace App\Entity;

enum Etats: string
{
    case Creee = 'Créee';
    case Ouverte = 'Ouverte';
    case Cloturee = 'Cloturée';
    case Encours = 'En cours';
    case Passee = 'Passée';
    case Annulee = 'Annulée';
}
