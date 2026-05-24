<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Entity\Etudiant;
use App\Entity\Seance;
use App\Entity\Note;
use App\Form\SeanceType;
use App\Form\EtudiantType;
use App\Form\NoteType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfController extends AbstractController
{
    #[Route('/', name: 'home_redirect')]
    public function homeRedirect(): Response {
        return $this->redirectToRoute('prof_login');
    }

    // 🔑 Connexion (démo simple, sans sécurité Symfony)
    #[Route('/login', name: 'prof_login')]
    public function login(Request $request, ManagerRegistry $doctrine): Response {
        $error = false;

        if ($request->isMethod('POST')) {
            $user = $request->request->get('username');
            $pass = $request->request->get('password');

            $repo = $doctrine->getRepository(Professeur::class);
            $prof = $repo->findOneBy(['username' => $user, 'password' => $pass]);

            if ($prof) {
                return $this->redirectToRoute('prof_accueil');
            }
            $error = true;
        }

        return $this->render('prof/login.html.twig', ['error' => $error]);
    }

    // 🏠 Accueil
    #[Route('/accueil', name: 'prof_accueil')]
    public function accueil(ManagerRegistry $doctrine): Response {
        $seanceCount = count($doctrine->getRepository(Seance::class)->findAll());
        $etudiantCount = count($doctrine->getRepository(Etudiant::class)->findAll());

        $groupStats = [];
        foreach (['G6','G4','G10'] as $g) {
            $groupStats[$g] = count($doctrine->getRepository(Etudiant::class)->findBy(['groupe' => $g]));
        }

        return $this->render('prof/accueil.html.twig', [
            'seanceCount' => $seanceCount,
            'etudiantCount' => $etudiantCount,
            'groupStats' => $groupStats
        ]);
    }

    // 📋 Séances — liste
    #[Route('/seances', name: 'prof_seances')]
    public function seances(ManagerRegistry $doctrine): Response {
        $seances = $doctrine->getRepository(Seance::class)->findAll();
        return $this->render('prof/seances.html.twig', ['seances' => $seances]);
    }

    // 📋 Séances — ajout
    #[Route('/seances/add', name: 'prof_add_seance')]
    public function addSeance(Request $request, ManagerRegistry $doctrine): Response {
        $seance = new Seance();
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($seance);
            $em->flush();

            $this->addFlash('success', 'Séance ajoutée avec succès ✅');
            return $this->redirectToRoute('prof_seances');
        }

        return $this->render('prof/add_seance.html.twig', [
            'form' => $form->createView()
        ]);
    }

    // 📋 Séances — édition
    #[Route('/seances/edit/{id}', name: 'prof_edit_seance')]
    public function editSeance(int $id, Request $request, ManagerRegistry $doctrine): Response {
        $em = $doctrine->getManager();
        $seance = $em->getRepository(Seance::class)->find($id);

        if (!$seance) {
            $this->addFlash('danger', 'Séance introuvable ❌');
            return $this->redirectToRoute('prof_seances');
        }

        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Séance modifiée avec succès ✏️');
            return $this->redirectToRoute('prof_seances');
        }

        return $this->render('prof/edit_seance.html.twig', [
            'form' => $form->createView(),
            'seance' => $seance
        ]);
    }

    // 📋 Séances — suppression
    #[Route('/seances/delete/{id}', name: 'prof_delete_seance', methods: ['POST','GET'])]
    public function deleteSeance(int $id, ManagerRegistry $doctrine, Request $request): Response {
        $em = $doctrine->getManager();
        $seance = $em->getRepository(Seance::class)->find($id);

        if (!$seance) {
            $this->addFlash('danger', 'Séance introuvable ❌');
            return $this->redirectToRoute('prof_seances');
        }

        // Option CSRF si bouton POST
        if ($request->isMethod('POST')) {
            $token = $request->request->get('_token');
            if (!$this->isCsrfTokenValid('delete_seance_'.$seance->getId(), $token)) {
                $this->addFlash('danger', 'Token CSRF invalide ❌');
                return $this->redirectToRoute('prof_seances');
            }
        }

        $em->remove($seance);
        $em->flush();

        $this->addFlash('success', 'Séance supprimée avec succès 🗑️');
        return $this->redirectToRoute('prof_seances');
    }

    // 👥 Groupes — liste et filtrage
    #[Route('/groupes', name: 'prof_groupes')]
    public function groupes(Request $request, ManagerRegistry $doctrine): Response {
        $selected = $request->query->get('groupe');
        $etudiants = $selected ? $doctrine->getRepository(Etudiant::class)->findBy(['groupe' => $selected]) : [];

        return $this->render('prof/groupes.html.twig', [
            'groups' => ['G6','G4','G10'],
            'selected' => $selected,
            'etudiants' => $etudiants
        ]);
    }

    // 👥 Groupes — ajout étudiant
    #[Route('/groupes/add/{groupe}', name: 'prof_add_etudiant')]
    public function addEtudiant(string $groupe, Request $request, ManagerRegistry $doctrine): Response {
        $etudiant = new Etudiant();
        $etudiant->setGroupe($groupe);

        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($etudiant);
            $em->flush();

            $this->addFlash('success', 'Étudiant ajouté avec succès ✅');
            return $this->redirectToRoute('prof_groupes', ['groupe' => $groupe]);
        }

        return $this->render('prof/add_etudiant.html.twig', [
            'form' => $form->createView(),
            'groupe' => $groupe
        ]);
    }

    // 👥 Groupes — suppression étudiant
    #[Route('/groupes/delete/{id}', name: 'prof_delete_etudiant', methods: ['POST','GET'])]
    public function deleteEtudiant(int $id, ManagerRegistry $doctrine, Request $request): Response {
        $em = $doctrine->getManager();
        $etudiant = $em->getRepository(Etudiant::class)->find($id);

        if (!$etudiant) {
            $this->addFlash('danger', 'Étudiant introuvable ❌');
            return $this->redirectToRoute('prof_groupes');
        }

        // Option CSRF si bouton POST
        if ($request->isMethod('POST')) {
            $token = $request->request->get('_token');
            if (!$this->isCsrfTokenValid('delete_etudiant_'.$etudiant->getId(), $token)) {
                $this->addFlash('danger', 'Token CSRF invalide ❌');
                return $this->redirectToRoute('prof_groupes', ['groupe' => $etudiant->getGroupe()]);
            }
        }

        $groupe = $etudiant->getGroupe();
        $em->remove($etudiant);
        $em->flush();

        $this->addFlash('success', 'Étudiant supprimé avec succès 🗑️');
        return $this->redirectToRoute('prof_groupes', ['groupe' => $groupe]);
    }

    // 📚 Modules — statique (démo)
    #[Route('/modules', name: 'prof_modules')]
    public function modules(): Response {
        $modules = [
            ['nom' => 'Mathématiques', 'cours' => ['Algèbre linéaire','Analyse 1','Analyse 2','Probabilités','Statistiques']],
            ['nom' => 'Physique', 'cours' => ['Mécanique','Électromagnétisme','Optique','Thermodynamique','Physique moderne']],
            ['nom' => 'Informatique', 'cours' => ['Programmation C','Programmation Python','Bases de données','Structures de données','Algorithmes']]
        ];

        return $this->render('prof/modules.html.twig', ['modules' => $modules]);
    }

    // 📝 Notes — ajout
#[Route('/notes/ajouter', name: 'prof_notes_ajouter')]
public function ajouterNotes(Request $request, ManagerRegistry $doctrine): Response {
    $note = new Note();
    $form = $this->createForm(NoteType::class, $note);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em = $doctrine->getManager();
        $em->persist($note);
        $em->flush();

        $this->addFlash('success', 'Note ajoutée avec succès ✅');
        return $this->redirectToRoute('prof_notes_voir');
    }

    // récupérer toutes les notes pour affichage
    $notes = $doctrine->getRepository(Note::class)->findAll();

    // préparer labels et values pour le graphique
    $labels = [];
    $values = [];
    foreach ($notes as $n) {
        if ($n->getEtudiant()) {
            $labels[] = $n->getEtudiant()->getNom();
        } else {
            $labels[] = "Inconnu";
        }
        $values[] = $n->getValeur();
    }

    return $this->render('prof/notes.html.twig', [
        'form' => $form->createView(),
        'notes' => $notes,
        'activeTab' => 'ajouter',
        'labels' => $labels,
        'values' => $values
    ]);
}

// 📝 Notes — voir (filtrage par groupe)
#[Route('/notes/voir', name: 'prof_notes_voir')]
public function voirNotes(Request $request, ManagerRegistry $doctrine): Response {
    $groupe = $request->query->get('groupe');
    $repo = $doctrine->getRepository(Note::class);
    $notes = $groupe ? $repo->findBy(['groupe' => $groupe]) : $repo->findAll();

    // préparer labels et values pour le graphique
    $labels = [];
    $values = [];
    foreach ($notes as $n) {
        if ($n->getEtudiant()) {
            $labels[] = $n->getEtudiant()->getNom();
        } else {
            $labels[] = "Inconnu";
        }
        $values[] = $n->getValeur();
    }

    return $this->render('prof/notes.html.twig', [
        'notes' => $notes,
        'form' => null,
        'activeTab' => 'voir',
        'labels' => $labels,
        'values' => $values
    ]);
}


// 📝 Notes — modifier
#[Route('/notes/edit/{id}', name: 'prof_edit_note')]
public function editNote(int $id, Request $request, ManagerRegistry $doctrine): Response {
    $em = $doctrine->getManager();
    $note = $em->getRepository(Note::class)->find($id);

    if (!$note) {
        $this->addFlash('danger', 'Note introuvable ❌');
        return $this->redirectToRoute('prof_notes_voir');
    }

    $form = $this->createForm(NoteType::class, $note);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $em->flush();
        $this->addFlash('success', 'Note modifiée avec succès ✏️');
        return $this->redirectToRoute('prof_notes_voir');
    }

    return $this->render('prof/edit_note.html.twig', [
        'form' => $form->createView(),
        'note' => $note
    ]);
}

// 📝 Notes — supprimer
#[Route('/notes/delete/{id}', name: 'prof_delete_note', methods: ['POST','GET'])]
public function deleteNote(int $id, ManagerRegistry $doctrine, Request $request): Response {
    $em = $doctrine->getManager();
    $note = $em->getRepository(Note::class)->find($id);

    if (!$note) {
        $this->addFlash('danger', 'Note introuvable ❌');
        return $this->redirectToRoute('prof_notes_voir');
    }

    // Option CSRF si bouton POST
    if ($request->isMethod('POST')) {
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_note_'.$note->getId(), $token)) {
            $this->addFlash('danger', 'Token CSRF invalide ❌');
            return $this->redirectToRoute('prof_notes_voir');
        }
    }

    $em->remove($note);
    $em->flush();

    $this->addFlash('success', 'Note supprimée avec succès 🗑️');
    return $this->redirectToRoute('prof_notes_voir');
}

    // 🚪 Déconnexion (démo simple)
    #[Route('/logout', name: 'prof_logout')]
    public function logout(): Response {
        return $this->redirectToRoute('prof_login');
    }
}