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

        $ville1 = new Villes();
        $ville1->setNom('La Rochelle');
        $ville1->setCodepostal('17000');
        $manager->persist($ville1);

        $ville2 = new Villes();
        $ville2->setNom('Rochefort');
        $ville2->setCodepostal('17300');
        $manager->persist($ville2);

        $ville3 = new Villes();
        $ville3->setNom('Poitier');
        $ville3->setCodepostal('86000');
        $manager->persist($ville3);

        $ville4 = new Villes();
        $ville4->setNom('Bordeaux');
        $ville4->setCodepostal('33000');
        $manager->persist($ville4);
        // ------------ LIEUX ------------
        $lieu = new Lieu();
        $lieu->setNom("Prison Island");
        $lieu->setVille($ville);
        $lieu->setRue("Zone commeciale Mendes France");
        $lieu->setLatitude(1.02668);
        $lieu->setLongitude(0.23654);
        $manager->persist($lieu);

        $lieu2 = new Lieu();
        $lieu2->setNom("Patinoire");
        $lieu2->setVille($ville);
        $lieu2->setRue("6 Rue des équarts");
        $lieu2->setLatitude(1.02668);
        $lieu2->setLongitude(0.23654);
        $manager->persist($lieu2);

        $lieu3 = new Lieu();
        $lieu3->setNom("Stade de foot");
        $lieu3->setVille($ville);
        $lieu3->setRue("8 Rue des équarts");
        $lieu3->setLatitude(1.02668);
        $lieu3->setLongitude(0.23654);
        $manager->persist($lieu3);

        $lieu3 = new Lieu();
        $lieu3->setNom("Stade de rugby");
        $lieu3->setVille($ville1);
        $lieu3->setRue("6 rue du stade");
        $lieu3->setLatitude(1.02668);
        $lieu3->setLongitude(0.23654);
        $manager->persist($lieu3);

        $lieu4 = new Lieu();
        $lieu4->setNom("Port des minimes");
        $lieu4->setVille($ville1);
        $lieu4->setRue("les minimes");
        $lieu4->setLatitude(1.02668);
        $lieu4->setLongitude(0.23654);
        $manager->persist($lieu4);

        $lieu5 = new Lieu();
        $lieu5->setNom("Corderie royale");
        $lieu5->setVille($ville2);
        $lieu5->setRue("rue de la corderie");
        $lieu5->setLatitude(1.02668);
        $lieu5->setLongitude(0.23654);
        $manager->persist($lieu5);

        $lieu6 = new Lieu();
        $lieu6->setNom("Futuroscope");
        $lieu6->setVille($ville3);
        $lieu6->setRue("6 rue du futur");
        $lieu6->setLatitude(1.02668);
        $lieu6->setLongitude(0.23654);
        $manager->persist($lieu6);

        // ------------ SITES ------------
        $site = new Sites();
        $site->setNom('ENI - Niort');
        $manager->persist($site);

        $site1 = new Sites();
        $site1->setNom('ENI - Nantes');
        $manager->persist($site1);

        $site2 = new Sites();
        $site2->setNom('ENI - Saint-Herblain');
        $manager->persist($site2);

        // ------------ UTILISATEURS ------------
        $utilisateur = new Utilisateur();
        $utilisateur->setEmail('f.delacour@gmail.com');
        $utilisateur->setRoles(["ROLE_ADMIN"]);
        $utilisateur->setImageProfil('photoDefaut.jpg');
        $password = $this->hasher->hashPassword($utilisateur, 'Pa$$w0rd');
        $utilisateur->setPassword($password);
        $utilisateur->setPseudo('SuperFleur');
        $utilisateur->setNom('Delacour');
        $utilisateur->setPrenom('Fleur');
        $utilisateur->setTelephone('0666666669');
        $utilisateur->setAdministrateur(true);
        $utilisateur->setActif(true);
        $utilisateur->setIdSite($site);
        $utilisateur->setImageProfil('fleurDelacours.jpg');
        $utilisateur->setHistoriser(false);
        $manager->persist($utilisateur);

        $utilisateur1 = new Utilisateur();
        $utilisateur1->setEmail('v.belgrade@gmail.com');
        $utilisateur1->setImageProfil('VictorKrum.jpg');
        $password = $this->hasher->hashPassword($utilisateur1, 'Pa$$w0rd');
        $utilisateur1->setPassword($password);
        $utilisateur1->setPseudo('Vivi');
        $utilisateur1->setNom('Belgrade');
        $utilisateur1->setPrenom('Victor');
        $utilisateur1->setTelephone('0625489654');
        $utilisateur1->setAdministrateur(false);
        $utilisateur1->setActif(true);
        $utilisateur1->setIdSite($site1);
        $utilisateur1->setHistoriser(false);
        $manager->persist($utilisateur1);

        $utilisateur2 = new Utilisateur();
        $utilisateur2->setEmail('m.monroe@gmail.com');
        $utilisateur2->setImageProfil('photoDefaut.jpg');
        $password = $this->hasher->hashPassword($utilisateur2, 'Pa$$w0rd');
        $utilisateur2->setPassword($password);
        $utilisateur2->setPseudo('Monroe17');
        $utilisateur2->setNom('Monroe');
        $utilisateur2->setPrenom('Marilyn');
        $utilisateur2->setTelephone('0698563214');
        $utilisateur2->setAdministrateur(false);
        $utilisateur2->setActif(true);
        $utilisateur2->setIdSite($site2);
        $utilisateur2->setHistoriser(false);
        $manager->persist($utilisateur2);

        $utilisateur = new Utilisateur();
        $utilisateur->setEmail('f.gaillard@gmail.com');
        $utilisateur->setImageProfil('photoDefaut.jpg');
        $password = $this->hasher->hashPassword($utilisateur, 'Pa$$w0rd');
        $utilisateur->setPassword($password);
        $utilisateur->setPseudo('fgaillard');
        $utilisateur->setNom('Gaillard');
        $utilisateur->setPrenom('Francis');
        $utilisateur->setTelephone('0662366659');
        $utilisateur->setAdministrateur(false);
        $utilisateur->setActif(true);
        $utilisateur->setIdSite($site2);
        $utilisateur->setImageProfil('photoDefaut.jpg');
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
