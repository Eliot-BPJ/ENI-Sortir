<?php

namespace App\DataFixtures;

use App\Entity\Etats;
use App\Entity\Lieu;
use App\Entity\Sites;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Entity\Villes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        // ------------ VILLES ------------
        $ville = new Villes();
        $ville->setNom('Niort');
        $ville->setCodepostal('79000');
        $manager->persist($ville);

        $ville = new Villes();
        $ville->setNom('La Rochelle');
        $ville->setCodepostal('17000');
        $manager->persist($ville);

        // ------------ LIEUX ------------
        $lieu = new Lieu();
        $lieu->setNom("Prison Island");
        $lieu->setVille($ville);
        $lieu->setRue("Zone commeciale Mendes France");
        $lieu->setLatitude(1.02668);
        $lieu->setLongitude(0.23654);

        // ------------ SITES ------------
        $site = new Sites();
        $site->setNom('ENI - Niort');
        $manager->persist($site);

        $site = new Sites();
        $site->setNom('ENI - Nantes');
        $manager->persist($site);

        // ------------ UTILISATEURS ------------
        $utilisateur = new Utilisateur();
        $utilisateur->setEmail('f.delacour@gmail.com');
        $utilisateur->setRoles(["ROLE_ADMIN"]);
        $utilisateur->setImageProfil('photoDefaut');
        $password = $this->hasher->hashPassword($utilisateur, 'Pa$$w0rd');
        $utilisateur->setPassword($password);
        $utilisateur->setPseudo('SuperFleur');
        $utilisateur->setNom('Delacour');
        $utilisateur->setPrenom('Fleur');
        $utilisateur->setTelephone('0666666669');
        $utilisateur->setAdministrateur(true);
        $utilisateur->setActif(true);
        $utilisateur->setIdSite($site);
        $utilisateur->setImageProfil('fleurDelacours-6525394eca12a.jpg');
        $utilisateur->setHistoriser(false);
        $manager->persist($utilisateur);

        $utilisateur = new Utilisateur();
        $utilisateur->setEmail('v.belgrade@gmail.com');
        $utilisateur->setImageProfil('photoDefaut');
        $password2 = $this->hasher->hashPassword($utilisateur, 'Pa$$w0rd');
        $utilisateur->setPassword($password2);
        $utilisateur->setPseudo('Vivi');
        $utilisateur->setNom('Belgrade');
        $utilisateur->setPrenom('Victor');
        $utilisateur->setTelephone('0625489654');
        $utilisateur->setAdministrateur(false);
        $utilisateur->setActif(true);
        $utilisateur->setIdSite($site);
        $utilisateur->setHistoriser(false);
        $manager->persist($utilisateur);

        $utilisateur = new Utilisateur();
        $utilisateur->setEmail('m.monroe@gmail.com');
        $utilisateur->setImageProfil('photoDefaut');
        $password2 = $this->hasher->hashPassword($utilisateur, 'Pa$$w0rd');
        $utilisateur->setPassword($password2);
        $utilisateur->setPseudo('Monroe17');
        $utilisateur->setNom('Monroe');
        $utilisateur->setPrenom('Marilyn');
        $utilisateur->setTelephone('0698563214');
        $utilisateur->setAdministrateur(false);
        $utilisateur->setActif(true);
        $utilisateur->setIdSite($site);
        $utilisateur->setHistoriser(false);
        $manager->persist($utilisateur);

        // ------------ SORTIE ------------
        $sortie = new Sortie();
        $sortie->setNom("Prison Island");
        $sortie->setDateDebut(new \DateTime('2023-12-01 14:00'));
        $sortie->setDuree(180);
        $sortie->setDateLimiteInscription(new \DateTime('2023-11-21 14:00'));
        $sortie->setNbInscriptionMax(9);
        $sortie->setInfosSortie("Sortie au Prison Island avec 3 équipes de 3");
        $sortie->setSite($site);
        $sortie->setLieux($lieu);
        $sortie->setEtat(Etats::Ouverte);
        $sortie->setOrganisateur($utilisateur);
        $sortie->setEstHistorise(false);
        $manager->persist($sortie);

        $sortie = new Sortie();
        $sortie->setNom("Prison");
        $sortie->setDateDebut(new \DateTime('2023-12-01 14:00'));
        $sortie->setDuree(180);
        $sortie->setDateLimiteInscription(new \DateTime('2023-11-21 14:00'));
        $sortie->setNbInscriptionMax(9);
        $sortie->setInfosSortie("Sortie au Prison Island avec 3 équipes de 3");
        $sortie->setSite($site);
        $sortie->setLieux($lieu);
        $sortie->setEtat(Etats::Ouverte);
        $sortie->setOrganisateur($utilisateur);
        $sortie->setEstHistorise(false);
        $manager->persist($sortie);

        $manager->flush();
    }
}
