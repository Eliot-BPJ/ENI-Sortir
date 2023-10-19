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
        //#region ------------ VILLES ------------
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
        //#endregion ------------ VILLES ------------

        //#region ------------ LIEUX ------------
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

        $lieu9 = new Lieu();
        $lieu9->setNom("Stade de rugby");
        $lieu9->setVille($ville1);
        $lieu9->setRue("6 rue du stade");
        $lieu9->setLatitude(1.02668);
        $lieu9->setLongitude(0.23654);
        $manager->persist($lieu9);

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

        $lieu7 = new Lieu();
        $lieu7->setNom("CGR Cimena");
        $lieu7->setVille($ville4);
        $lieu7->setRue("Place gambetta");
        $lieu7->setLatitude(1.02668);
        $lieu7->setLongitude(0.23654);
        $manager->persist($lieu7);

        $lieu8 = new Lieu();
        $lieu8->setNom("Ecole ENI");
        $lieu8->setVille($ville);
        $lieu8->setRue("16 rue Léo Lagrange");
        $lieu8->setLatitude(1.02668);
        $lieu8->setLongitude(0.23654);
        $manager->persist($lieu8);
        //#endregion ------------ LIEUX ------------

        //#region ------------ SITES ------------
        $site = new Sites();
        $site->setNom('ENI - Niort');
        $manager->persist($site);

        $site1 = new Sites();
        $site1->setNom('ENI - Nantes');
        $manager->persist($site1);

        $site2 = new Sites();
        $site2->setNom('ENI - Saint-Herblain');
        $manager->persist($site2);
        //#endregion ------------ SITES ------------

        //#region ------------ UTILISATEURS ------------
        $utilisateur = new Utilisateur();
        $utilisateur->setEmail('f.delacour@gmail.com');
        $utilisateur->setRoles(["ROLE_ADMIN"]);
        $utilisateur->setImageProfil('fleurDelacours.jpg');
        $password = $this->hasher->hashPassword($utilisateur, 'Pa$$w0rd');
        $utilisateur->setPassword($password);
        $utilisateur->setPseudo('SuperFleur');
        $utilisateur->setNom('Delacour');
        $utilisateur->setPrenom('Fleur');
        $utilisateur->setTelephone('0666666669');
        $utilisateur->setAdministrateur(true);
        $utilisateur->setActif(true);
        $utilisateur->setIdSite($site);
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

        $utilisateur3 = new Utilisateur();
        $utilisateur3->setEmail('f.gaillard@gmail.com');
        $utilisateur3->setImageProfil('photoDefaut.jpg');
        $password = $this->hasher->hashPassword($utilisateur3, 'Pa$$w0rd');
        $utilisateur3->setPassword($password);
        $utilisateur3->setPseudo('fgaillard');
        $utilisateur3->setNom('Gaillard');
        $utilisateur3->setPrenom('Francis');
        $utilisateur3->setTelephone('0662366659');
        $utilisateur3->setAdministrateur(false);
        $utilisateur3->setActif(true);
        $utilisateur3->setIdSite($site2);
        $utilisateur3->setHistoriser(false);
        $manager->persist($utilisateur3);

        $utilisateur4 = new Utilisateur();
        $utilisateur4->setEmail('f.lefrancaisd@gmail.com');
        $utilisateur4->setImageProfil('photoDefaut.jpg');
        $password = $this->hasher->hashPassword($utilisateur4, 'Pa$$w0rd');
        $utilisateur4->setPassword($password);
        $utilisateur4->setPseudo('franfran');
        $utilisateur4->setNom('Le Français');
        $utilisateur4->setPrenom('François');
        $utilisateur4->setTelephone('0662366659');
        $utilisateur4->setAdministrateur(false);
        $utilisateur4->setActif(true);
        $utilisateur4->setIdSite($site1);
        $utilisateur4->setImageProfil('photoDefaut.jpg');
        $utilisateur4->setHistoriser(false);
        $manager->persist($utilisateur4);

        $utilisateur5 = new Utilisateur();
        $utilisateur5->setEmail('guillaume@gmail.com');
        $utilisateur5->setImageProfil('photoDefaut.jpg');
        $password = $this->hasher->hashPassword($utilisateur5, 'Pa$$w0rd');
        $utilisateur5->setPassword($password);
        $utilisateur5->setPseudo('guigui');
        $utilisateur5->setNom('Duchêne');
        $utilisateur5->setPrenom('Guillaume');
        $utilisateur5->setTelephone('0254685474');
        $utilisateur5->setAdministrateur(false);
        $utilisateur5->setActif(true);
        $utilisateur5->setIdSite($site);
        $utilisateur5->setHistoriser(false);
        $manager->persist($utilisateur5);
        //#endregion ------------ UTILISATEURS ------------

        //#region ------------ SORTIE ------------
        $sortie = new Sortie();
        $sortie->setNom("Prison Island");
        $sortie->setDateDebut(new \DateTime('2023-12-01 14:00'));
        $sortie->setDuree(180);
        $sortie->setDateLimiteInscription(new \DateTime('2023-11-21 14:00'));
        $sortie->setNbInscriptionMax(9);
        $sortie->setInfosSortie("Sortie au Prison Island avec 3 équipes de 3");
        $sortie->setSite($site);
        $sortie->setLieux($lieu);
        $sortie->setEtat(Etats::Creee);
        $sortie->setOrganisateur($utilisateur);
        $sortie->setEstHistorise(false);
        $manager->persist($sortie);

        $sortie1 = new Sortie();
        $sortie1->setNom("Patinoire");
        $sortie1->setDateDebut(new \DateTime('2023-12-01 14:00'));
        $sortie1->setDuree(180);
        $sortie1->setDateLimiteInscription(new \DateTime('2023-11-21 14:00'));
        $sortie1->setNbInscriptionMax(10);
        $sortie1->setInfosSortie("Sortie à la patinoire de niort.");
        $sortie1->setSite($site);
        $sortie1->setLieux($lieu2);
        $sortie1->setEtat(Etats::Ouverte);
        $sortie1->setOrganisateur($utilisateur5);
        $sortie1->setEstHistorise(false);
        $manager->persist($sortie1);

        $sortie2 = new Sortie();
        $sortie2->setNom("Match de foot : Les Chamois -  PSG");
        $sortie2->setDateDebut(new \DateTime('2023-05-01 18:00'));
        $sortie2->setDuree(90);
        $sortie2->setDateLimiteInscription(new \DateTime('2023-04-01 12:00'));
        $sortie2->setNbInscriptionMax(4);
        $sortie2->setInfosSortie("Match exeptionnel contre le PSG. Inscription importante pour réserver les places !!!");
        $sortie2->setSite($site);
        $sortie2->setLieux($lieu3);
        $sortie2->setEtat(Etats::Passee);
        $sortie2->setOrganisateur($utilisateur);
        $sortie2->setEstHistorise(true);
        $manager->persist($sortie2);

        $sortie3 = new Sortie();
        $sortie3->setNom("Match de rugby : La Rochelle - Bordeaux");
        $sortie3->setDateDebut(new \DateTime('2023-10-05 18:00'));
        $sortie3->setDuree(80);
        $sortie3->setDateLimiteInscription(new \DateTime('2023-09-05 12:00'));
        $sortie3->setNbInscriptionMax(6);
        $sortie3->setInfosSortie("Match de Top14 à La Rochelle.");
        $sortie3->setSite($site);
        $sortie3->setLieux($lieu9);
        $sortie3->setEtat(Etats::Passee);
        $sortie3->setOrganisateur($utilisateur5);
        $sortie3->setEstHistorise(false);
        $manager->persist($sortie3);

        $sortie4 = new Sortie();
        $sortie4->setNom("Pique-Nique à La Rochelle");
        $sortie4->setDateDebut(new \DateTime('2023-10-21 12:00'));
        $sortie4->setDuree(180);
        $sortie4->setDateLimiteInscription(new \DateTime('2023-10-21 11:00'));
        $sortie4->setNbInscriptionMax(20);
        $sortie4->setInfosSortie("Pique-nique au bord de la plage des minimes.");
        $sortie4->setSite($site1);
        $sortie4->setLieux($lieu4);
        $sortie4->setEtat(Etats::Ouverte);
        $sortie4->setOrganisateur($utilisateur1);
        $sortie4->setEstHistorise(false);
        $manager->persist($sortie4);

        $sortie5 = new Sortie();
        $sortie5->setNom("Repas de Noël et feu d'artifice à Rochefort");
        $sortie5->setDateDebut(new \DateTime('2023-12-25 23:00'));
        $sortie5->setDuree(180);
        $sortie5->setDateLimiteInscription(new \DateTime('2023-12-01 12:00'));
        $sortie5->setNbInscriptionMax(20);
        $sortie5->setInfosSortie("Feu d'arifice à la Corderie Royale de Rochefort avec prepas sur place.");
        $sortie5->setSite($site2);
        $sortie5->setLieux($lieu);
        $sortie5->setEtat(Etats::Creee);
        $sortie5->setOrganisateur($utilisateur2);
        $sortie5->setEstHistorise(false);
        $manager->persist($sortie5);

        $sortie6 = new Sortie();
        $sortie6->setNom("Journée au futuroscope");
        $sortie6->setDateDebut(new \DateTime('2023-11-04 09:00'));
        $sortie6->setDuree(420);
        $sortie6->setDateLimiteInscription(new \DateTime('2023-10-27 12:00'));
        $sortie6->setNbInscriptionMax(20);
        $sortie6->setInfosSortie("Journée au futuroscope. Prévoir de quoi manger sur place.");
        $sortie6->setSite($site2);
        $sortie6->setLieux($lieu6);
        $sortie6->setEtat(Etats::Ouverte);
        $sortie6->setOrganisateur($utilisateur1);
        $sortie6->setEstHistorise(false);
        $manager->persist($sortie6);

        $sortie7 = new Sortie();
        $sortie7->setNom("Cine-concert le seigneur des anneaux");
        $sortie7->setDateDebut(new \DateTime('2023-10-28 18:00'));
        $sortie7->setDuree(180);
        $sortie7->setDateLimiteInscription(new \DateTime('2023-10-10 12:00'));
        $sortie7->setNbInscriptionMax(2);
        $sortie7->setInfosSortie("Voir le film le seigneur des anneaux en ciné-concert à bordeaux.");
        $sortie7->setSite($site);
        $sortie7->setLieux($lieu7);
        $sortie7->setEtat(Etats::Passee);
        $sortie7->setOrganisateur($utilisateur);
        $sortie7->setEstHistorise(false);
        $manager->persist($sortie7);

        $sortie8 = new Sortie();
        $sortie8->setNom("Présentation du projet");
        $sortie8->setDateDebut(new \DateTime('2023-10-18 09:00'));
        $sortie8->setDuree(480);
        $sortie8->setDateLimiteInscription(new \DateTime('2023-10-17 17:00'));
        $sortie8->setNbInscriptionMax(20);
        $sortie8->setInfosSortie("Présentation à la promo du site Sortir.com");
        $sortie8->setSite($site);
        $sortie8->setLieux($lieu8);
        $sortie8->setEtat(Etats::Encours);
        $sortie8->setOrganisateur($utilisateur);
        $sortie8->setEstHistorise(false);
        $manager->persist($sortie8);
    //#endregion ------------ SORTIE ------------

        $manager->flush();
    }
}
