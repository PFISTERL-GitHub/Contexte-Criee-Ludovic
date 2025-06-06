<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/Criee8.css');?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
<body>  
<section id="connexion_et_inscription" class="connexion_et_inscription">
	<img src="<?php echo base_url('assets/img/Accueil1.webp');?>" class="Noir">
        <div class="card">
            <div class="loader">
                <div class="words">
                <span class="word">‎ </span>  
                    <span class="word">Welcome</span>  
                    <span class="word">Bienvenu(e)</span>  
                    <span class="word">Willkommen</span>  
                    <span class="word">Bem-vinda</span>  

                    <span class="word">Добро пожало</span>  <!-- pour l'espacement -->
                </div>
                <p>sur le site de la Criee de Poulgoazec</p>
            </div>
        </div>
        <h1>Découvrez notre sélection de ventes de poissons</h1>
        <br><br>
    </section>  

    <section id="features" class="features">
        <div class="container">
            <h2>Nos Services</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <i class="fas fa-fish"></i>
                    <h3>Vente aux Enchères</h3>
                    <p>Participez à nos ventes aux enchères en ligne de produits frais de la mer</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-ship"></i>
                    <h3>Pêche Locale</h3>
                    <p>Des produits issus directement des bateaux de pêche locaux</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-certificate"></i>
                    <h3>Qualité Garantie</h3>
                    <p>Une sélection rigoureuse des meilleurs produits de la mer</p>
                </div>
            </div>
        </div>
    </section>

    <?php if (!isset($_SESSION['identifiant'])) : ?>
    <section id="cta" class="cta">
        <div class="container">
            <h2>Rejoignez-nous</h2>
            <p>Créez votre compte pour participer aux enchères</p>
            <div class="cta-buttons">
                <a href="<?php echo site_url('welcome/contenu/Connexion'); ?>" class="btn btn-secondary">Se connecter</a>
                <a href="<?php echo site_url('welcome/contenu/Inscription'); ?>" class="btn btn-secondary">S'inscrire</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (isset($_SESSION['identifiant'])) : ?>
    <section id="latest" class="latest">
        <div class="container">
            <h2>Dernières Enchères</h2>
            <div class="auction-grid">
                <?php
                include "application/config/database.php";
                
                // Définir le fuseau horaire à Paris
                date_default_timezone_set('Europe/Paris');
                
                // Affiche les 3 dernières annonces enchéries 
                $query = "SELECT DISTINCT a.idImage, a.idBateau, a.titreAnnonce, a.prixEnchere, a.dateFinEnchere, a.DateEnchere
                         FROM ANNONCE a
                         WHERE a.DateEnchere > NOW() OR (a.DateEnchere <= NOW() AND a.dateFinEnchere > NOW())
                         ORDER BY a.DateEnchere ASC
                         LIMIT 3";
                
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $latest_auctions = $stmt->fetchAll();

                if (empty($latest_auctions)) {
                    echo '<div class="no-auctions">Aucune enchère n\'est disponible pour le moment.<br>Revenez plus tard pour de nouvelles enchères !</div>';
                } else {
                    foreach ($latest_auctions as $auction) :
                    ?>
                    <div class="auction-card">
                        <img src="<?php echo base_url('assets/' . $auction['idImage']); ?>" alt="<?php echo htmlspecialchars($auction['titreAnnonce']); ?>">
                        <div class="auction-info">
                            <h3><?php echo htmlspecialchars($auction['titreAnnonce']); ?></h3>
                            <p class="price">Prix actuel : <?php echo number_format($auction['prixEnchere'], 2); ?> €</p>
                            <?php 
                            $maintenant = time();
                            $dateEnchere = strtotime($auction['DateEnchere']);
                            $dateFinEnchere = strtotime($auction['dateFinEnchere']);
                            
                            if ($maintenant < $dateEnchere) : ?>
                                <p class="time">Début : <?php echo date('d/m/Y H:i', $dateEnchere); ?></p>
                                <p class="status" style="color: orange;">À venir</p>
                            <?php elseif ($maintenant > $dateFinEnchere) : ?>
                                <p class="time">Terminée le : <?php echo date('d/m/Y H:i', $dateFinEnchere); ?></p>
                                <p class="status" style="color: red;">Enchère terminée</p>
                            <?php else : ?>
                                <p class="time">Fin : <?php echo date('d/m/Y H:i', $dateFinEnchere); ?></p>
                                <p class="status" style="color: green;">En cours</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach;
                }
                ?>
            </div>
            <div class="view-all">
                <a href="<?php echo site_url('welcome/contenu/Annonces'); ?>" class="btn">Voir toutes les enchères</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section id="info-contact" class="info-contact">
        <div class="container">
            <h2>Vous souhaitez nous contacter ou avoir plus d'informations ?</h2>
            <div class="info-buttons">
                <a href="<?php echo site_url('welcome/contenu/Contact'); ?>" class="btn btn-info">Contact</a>
                <a href="<?php echo site_url('welcome/contenu/Mentions'); ?>" class="btn btn-info">Mentions légales</a>
                <a href="<?php echo site_url('welcome/contenu/Horaires'); ?>" class="btn btn-info">Horaires</a>
            </div>
        </div>
    </section>

    <script>
        let clickCount = 0;
        const logo = document.querySelector('.Noir');
        const audio = new Audio('<?php echo base_url("assets/audio/Poule.mp3"); ?>');
        const audio1 = new Audio('<?php echo base_url("assets/audio/Terre.mp3"); ?>');
        audio1.volume = 0.7; // Réduire le volume de Terre.mp3 à 30%
        
        // Préchargement des fichiers audio
        audio.load();
        audio1.load();
        
        // Gestion des erreurs de chargement
        audio.addEventListener('error', function(e) {
            console.error('Erreur lors du chargement du fichier audio Poule.mp3:', e);
        });
        
        audio1.addEventListener('error', function(e) {
            console.error('Erreur lors du chargement du fichier audio Terre.mp3:', e);
        });

        logo.addEventListener('click', function() {
            clickCount++;
            
            if (clickCount === 5) {
                // Activer l'effet
                document.body.classList.add('shake');
                
                // Jouer les sons avec gestion des erreurs
                try {
                    audio.currentTime = 0; // Réinitialiser le son
                    audio.play().catch(function(error) {
                        console.error('Erreur lors de la lecture de Poule.mp3:', error);
                    });
                    
                    audio1.currentTime = 0; // Réinitialiser le son
                    audio1.play().catch(function(error) {
                        console.error('Erreur lors de la lecture de Terre.mp3:', error);
                    });
                } catch (error) {
                    console.error('Erreur lors de la lecture des sons:', error);
                }
                
                // Créer la pluie de logos
                for (let i = 0; i < 100; i++) {
                    const fallingLogo = document.createElement('img');
                    fallingLogo.src = "<?php echo base_url('assets/img/Accueil.webp'); ?>";
                    fallingLogo.className = 'logo-rain';
                    fallingLogo.style.left = Math.random() * window.innerWidth + 'px';
                    fallingLogo.style.top = -100 + 'px';
                    fallingLogo.style.width = Math.random() * 100 + 50 + 'px';
                    fallingLogo.style.animationDuration = Math.random() * 2 + 2 + 's';
                    fallingLogo.style.animationDelay = Math.random() * 2 + 's';
                    document.body.appendChild(fallingLogo);
                    
                    // Supprimer le logo après l'animation
                    setTimeout(() => {
                        fallingLogo.remove();
                    }, 5000);
                }
                
                // Réinitialiser le compteur après 10 secondes
                setTimeout(() => {
                    clickCount = 0;
                    document.body.classList.remove('shake');
                }, 10000);
            }
        });
    </script>
</body>
</html>