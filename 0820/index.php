<?php
// ===================================================================
// BLOCO DE CONTROLO E LÓGICA PHP - VERSÃO FINAL CORRIGIDA
// ===================================================================
session_start();

// --- Processamento de Formulários (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'do_login':
            $username_attempt = $_POST['username'] ?? '';
            $password_attempt = $_POST['password'] ?? '';
            $user_found = false;

            if (isset($_SESSION['user_accounts']) && is_array($_SESSION['user_accounts'])) {
                foreach ($_SESSION['user_accounts'] as $account) {
                    if ($account['username'] === $username_attempt && $account['password'] === $password_attempt) {
                        $_SESSION['loggedin'] = true;
                        $_SESSION['user'] = $account;
                        if (isset($account['profile'])) { $_SESSION['profile'] = $account['profile']; }
                        header("Location: index.php?page=feed"); exit;
                    }
                }
            }
            $_SESSION['errors']['login'] = "Credenciais inválidas. Tente novamente ou crie uma conta.";
            header("Location: index.php?page=login"); exit;

        case 'do_signup':
            $errors = []; $_SESSION['old_data'] = $_POST;
            $username = trim($_POST['username'] ?? ''); $email = trim($_POST['email'] ?? ''); $password = $_POST['password'] ?? '';
            
            if (isset($_SESSION['user_accounts'])) {
                foreach ($_SESSION['user_accounts'] as $account) {
                    if ($account['username'] === $username) { $errors['username'] = 'Este nome de utilizador já existe.'; }
                }
            }
            if (strlen($username) < 3) $errors['username'] = 'Utilizador deve ter pelo menos 3 caracteres.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Por favor, insira um email válido.';
            if (strlen($password) < 8) $errors['password'] = 'Senha deve ter pelo menos 8 caracteres.';
            
            if (empty($errors)) {
                $new_account = [ 'username' => htmlspecialchars($username), 'email' => htmlspecialchars($email), 'password' => $password ];
                $_SESSION['user_accounts'][] = $new_account;
                $_SESSION['loggedin'] = true; $_SESSION['user'] = $new_account;
                unset($_SESSION['old_data']);
                header("Location: index.php?page=create_profile"); exit;
            } else {
                $_SESSION['errors'] = $errors;
                header("Location: index.php?page=signup"); exit;
            }

        case 'do_profile':
            $errors = []; $_SESSION['old_data'] = $_POST;
            $fullname = trim($_POST['fullname'] ?? '');
            if (count(explode(' ', $fullname)) < 2) $errors['fullname'] = 'Por favor, insira o seu nome completo.';
            if (empty($_POST['dob'])) $errors['dob'] = 'Data de nascimento é obrigatória.';

            if (empty($errors)) {
                 $profile_data = [
                    'fullname' => htmlspecialchars($fullname), 'dob' => $_POST['dob'],
                    'pic' => !empty($_POST['pic']) ? htmlspecialchars($_POST['pic']) : 'https://i.pravatar.cc/150?u=' . time(),
                    'location' => htmlspecialchars(trim($_POST['location'] ?? '')),
                    'telephone' => htmlspecialchars(trim($_POST['telephone'] ?? '')),
                    'bio' => htmlspecialchars(trim($_POST['bio'] ?? ''))
                ];
                $logged_user_username = $_SESSION['user']['username'];
                foreach ($_SESSION['user_accounts'] as $key => $account) {
                    if ($account['username'] === $logged_user_username) { $_SESSION['user_accounts'][$key]['profile'] = $profile_data; break; }
                }
                $_SESSION['profile'] = $profile_data;
                unset($_SESSION['old_data']);
                header("Location: index.php?page=feed"); exit;
            } else {
                $_SESSION['errors'] = $errors;
                header("Location: index.php?page=create_profile"); exit;
            }
        
        case 'do_post':
            $post_content = trim($_POST['post_content'] ?? '');
            if ($post_content) {
                $new_post = [ 'authorName' => $_SESSION['profile']['fullname'], 'authorPic' => $_SESSION['profile']['pic'], 'timestamp' => 'Agora', 'text' => htmlspecialchars($post_content), 'postImage' => null ];
                if (!isset($_SESSION['posts'])) $_SESSION['posts'] = [];
                array_unshift($_SESSION['posts'], $new_post);
            }
            header("Location: index.php?page=feed"); exit;
        
        case 'do_contact':
            $contact_name = trim($_POST['contact_name'] ?? ''); $contact_email = trim($_POST['contact_email'] ?? ''); $contact_message = trim($_POST['contact_message'] ?? '');
            if (!empty($contact_name) && filter_var($contact_email, FILTER_VALIDATE_EMAIL) && !empty($contact_message)) {
                $_SESSION['contact_submission'] = [ 'name' => htmlspecialchars($contact_name), 'email' => htmlspecialchars($contact_email), 'message' => htmlspecialchars($contact_message) ];
                header("Location: index.php?page=contact_success"); exit;
            } else {
                $_SESSION['errors']['contact'] = "Por favor, preencha todos os campos corretamente.";
                header("Location: index.php?page=contact"); exit;
            }
    }
}

// --- Controlo de Navegação (GET) e Funções Auxiliares ---
$page = $_GET['page'] ?? 'landing';
if ($page === 'logout') { session_destroy(); header("Location: index.php"); exit; }
$loggedInPages = ['feed', 'create_profile', 'profile_view', 'contact', 'contact_success'];
if (in_array($page, $loggedInPages) && !isset($_SESSION['loggedin'])) { header("Location: index.php?page=login"); exit; }
function getAge($dateStr) { if(!$dateStr) return 0; try { $birthDate = new DateTime($dateStr); $today = new DateTime('today'); return $birthDate->diff($today)->y; } catch (Exception $e) { return 0; } }
if (!isset($_SESSION['posts'])) { $_SESSION['posts'] = [ ['authorName' => "Admin", 'authorPic' => "https://i.pravatar.cc/150?u=admin", 'timestamp' => "Bem-vindo", 'text' => "Este é o início do seu feed na ConectaRede!", 'postImage' => null] ]; }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConectaRede - Por Vitor Rafael</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root { --bg-gradient: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab); --glass-bg: rgba(255, 255, 255, 0.15); --glass-border: rgba(255, 255, 255, 0.2); --text-color: #f0f0f0; --input-bg: rgba(0, 0, 0, 0.2); --button-bg: rgba(255, 255, 255, 0.25); --button-hover-bg: rgba(255, 255, 255, 0.4); --border-radius: 20px; --color-success: #22c55e; --color-danger: #ef4444; --color-warning: #f59e0b; }
        @keyframes gradientShift { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.03); } 100% { transform: scale(1); } }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; color: var(--text-color); background: var(--bg-gradient); background-size: 400% 400%; animation: gradientShift 15s ease infinite; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 2rem; padding-top: <?php echo (in_array($page, $loggedInPages) ? '8rem' : '2rem'); ?>; }
        .logo-container { position: fixed; top: 2rem; left: 2rem; display: flex; align-items: center; gap: 0.75rem; text-decoration: none; z-index: 1000; transition: transform 0.3s; }
        .logo-container:hover { transform: scale(1.05); }
        .logo-svg { width: 40px; height: 40px; }
        .logo-text { font-size: 1.5rem; font-weight: 700; color: var(--text-color); text-shadow: 0 2px 10px rgba(0,0,0,0.2); }
        .page-container { width: 100%; max-width: 600px; animation: fadeIn 0.5s ease-in-out; }
        .glass-container { background: var(--glass-bg); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid var(--glass-border); border-radius: var(--border-radius); box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2); width: 100%; overflow: hidden; }
        .content-padding { padding: 2.5rem; }
        h1 { font-size: 2.2rem; margin-bottom: 1rem; font-weight: 700; text-align: center; }
        .page-title { font-size: 1.8rem; text-transform: uppercase; letter-spacing: 2px; text-align: center; margin-bottom: 2rem;}
        .header-text { font-size: 1.2rem; line-height: 1.6; margin-bottom: 2.5rem; opacity: 0.9; text-align: center; }
        .btn { display: block; width: 100%; padding: 1rem; font-size: 1.1rem; color: var(--text-color); background-color: var(--button-bg); border: none; border-radius: 15px; cursor: pointer; text-decoration: none; text-align: center; transition: background-color 0.3s; font-weight: 500;}
        .btn:hover { background-color: var(--button-hover-bg); }
        .landing-btn { animation: pulse 2.5s infinite; }
        .form-group { margin-bottom: 1rem; }
        .form-control, textarea { width: 100%; padding: 0.8rem 1rem; background-color: var(--input-bg); border: 1px solid var(--glass-border); border-radius: 15px; color: var(--text-color); font-size: 1rem; font-family: 'Poppins', sans-serif; }
        textarea { resize: vertical; min-height: 80px; }
        .error-message { color: #ffc4c4; font-size: 0.8rem; margin-top: 0.3rem; min-height: 1em; }
        .app-nav { position: fixed; top: 2rem; right: 2rem; background: var(--glass-bg); backdrop-filter: blur(10px); border-radius: 50px; padding: 0.5rem; z-index: 1000; display: flex; align-items: center; gap: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .app-nav a { color: var(--text-color); text-decoration: none; font-weight: 500; }
        #nav-profile-pic { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid var(--glass-border); }
        #page-feed .glass-container, #page-view-profile .glass-container { padding: 0; }
        .create-post { background-color: rgba(0,0,0,0.1); padding: 1rem; border-radius: 15px; margin: 1.5rem 1.5rem 0; }
        .post-card { background: rgba(0,0,0,0.1); border-radius: 15px; margin: 1.5rem; padding: 1.5rem; }
        .post-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
        .post-author-pic { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .post-author-name { font-weight: 700; }
        .post-timestamp { font-size: 0.8rem; opacity: 0.7; margin-left: auto; }
        .post-image { width: 100%; border-radius: 10px; margin-top: 1rem; }
        .profile-banner { height: 120px; background: linear-gradient(to right, rgba(231, 60, 126, 0.5), rgba(35, 166, 213, 0.5)); }
        .profile-pic-wrapper { text-align: center; margin-top: -60px; }
        .profile-view-pic { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--glass-bg); }
        .profile-info { padding: 1.5rem 2.5rem; text-align: center; }
        .profile-view-name { font-size: 1.8rem; }
        .profile-meta { display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 1.5rem; margin-bottom: 1rem; }
        .meta-item { display: flex; align-items: center; gap: 0.5rem; }
        .profile-actions { display: flex; gap: 1rem; margin-top: 2rem; padding: 0 2.5rem 2.5rem; }
        .credits { text-align: center; margin-top: 2.5rem; font-size: 0.9rem; opacity: 0.8; }
        .extra-links { text-align: center; margin-top: 1.5rem; }
        .confirmation-box { background-color: rgba(0,0,0,0.1); padding: 1.5rem; border-radius: 15px; margin-top: 1rem; text-align: left; }
        .confirmation-box p { margin-bottom: 0.5rem; word-wrap: break-word; }
        .help-button { position: fixed; bottom: 2rem; right: 2rem; background-color: var(--button-bg); color: var(--text-color); width: 60px; height: 60px; border-radius: 50%; display: flex; justify-content: center; align-items: center; text-decoration: none; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s ease; z-index: 1001; }
        .help-button:hover { transform: scale(1.1); background-color: var(--button-hover-bg); }
        .help-button svg { width: 32px; height: 32px; }
        @media (max-width: 600px) { .logo-text { display: none; } .logo-container { top: 1.5rem; left: 1.5rem; } body { padding: 1rem; padding-top: 7rem; } .app-nav { top: auto; bottom: 1.5rem; left: 50%; transform: translateX(-50%); } .help-button { width: 50px; height: 50px; bottom: 6rem; } }
    </style>
</head>
<body>

    <a href="index.php" class="logo-container"> </a>
    <?php if (isset($_SESSION['loggedin'])): ?>
    <nav class="app-nav">
        <a href="index.php?page=feed">Feed</a>
        <a href="index.php?page=profile_view" title="Meu Perfil">
            <img id="nav-profile-pic" src="<?php echo htmlspecialchars($_SESSION['profile']['pic'] ?? 'https://i.pravatar.cc/150'); ?>">
        </a>
    </nav>
    <a href="index.php?page=contact" class="help-button" title="Ajuda e Contato">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.89 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"></path></svg>
    </a>
    <?php endif; ?>

    <div class="page-container">
    <?php
    switch ($page) {
        case 'login': ?>
            <div class="glass-container content-padding">
                <h1 class="page-title">Login</h1>
                <p class="error-message" style="text-align:center; margin-bottom:1rem;"><?php echo $_SESSION['errors']['login'] ?? ''; ?></p>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="do_login">
                    <div class="form-group"><input type="text" name="username" class="form-control" placeholder="Utilizador" required></div>
                    <div class="form-group"><input type="password" name="password" class="form-control" placeholder="Senha" required></div>
                    <button type="submit" class="btn">Entrar</button>
                </form>
                <div class="extra-links"><p>Não tem uma conta? <a href="index.php?page=signup">Crie uma aqui</a></p></div>
            </div>
            <?php break;
        case 'signup': ?>
            <div class="glass-container content-padding">
                <h1 class="page-title">Registo</h1>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="do_signup">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="Nome de Utilizador" required value="<?php echo htmlspecialchars($_SESSION['old_data']['username'] ?? ''); ?>">
                        <p class="error-message"><?php echo $_SESSION['errors']['username'] ?? ''; ?></p>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="E-mail" required value="<?php echo htmlspecialchars($_SESSION['old_data']['email'] ?? ''); ?>">
                        <p class="error-message"><?php echo $_SESSION['errors']['email'] ?? ''; ?></p>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Senha (mínimo 8 caracteres)" required>
                        <p class="error-message"><?php echo $_SESSION['errors']['password'] ?? ''; ?></p>
                    </div>
                    <button type="submit" class="btn">Continuar</button>
                </form>
                <div class="extra-links"><p>Já tem uma conta? <a href="index.php?page=login">Faça o login</a></p></div>
            </div>
            <?php break;
        case 'create_profile': 
            $profile = $_SESSION['profile'] ?? []; $old_data = $_SESSION['old_data'] ?? []; ?>
            <div class="glass-container content-padding">
                <h1 class="page-title">Complete o seu Perfil</h1>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="do_profile">
                    <div class="form-group"><label>Nome Completo</label><input type="text" name="fullname" class="form-control" required value="<?php echo htmlspecialchars($old_data['fullname'] ?? $profile['fullname'] ?? ''); ?>"><p class="error-message"><?php echo $_SESSION['errors']['fullname'] ?? ''; ?></p></div>
                    <div class="form-group"><label>Data de Nascimento</label><input type="date" name="dob" class="form-control" required value="<?php echo htmlspecialchars($old_data['dob'] ?? $profile['dob'] ?? ''); ?>"><p class="error-message"><?php echo $_SESSION['errors']['dob'] ?? ''; ?></p></div>
                    <div class="form-group"><label>URL da Foto de Perfil</label><input type="url" name="pic" class="form-control" value="<?php echo htmlspecialchars($old_data['pic'] ?? $profile['pic'] ?? ''); ?>"></div>
                    <div class="form-group"><label>Telefone</label><input type="tel" name="telephone" class="form-control" value="<?php echo htmlspecialchars($old_data['telephone'] ?? $profile['telephone'] ?? ''); ?>"></div>
                    <div class="form-group"><label>Localização</label><input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($old_data['location'] ?? $profile['location'] ?? ''); ?>"></div>
                    <div class="form-group"><label>Sua Biografia</label><textarea name="bio" class="form-control"><?php echo htmlspecialchars($old_data['bio'] ?? $profile['bio'] ?? ''); ?></textarea></div>
                    <button type="submit" class="btn">Salvar Perfil</button>
                </form>
            </div>
            <?php break;
        case 'feed': ?>
            <div class="glass-container">
                <div class="create-post">
                    <form action="index.php" method="POST">
                        <input type="hidden" name="action" value="do_post">
                        <textarea name="post_content" placeholder="No que você está a pensar, <?php echo htmlspecialchars(explode(' ', $_SESSION['profile']['fullname'])[0]); ?>?" class="form-control"></textarea>
                        <button type="submit" class="btn" style="margin-top: 0.5rem;">Publicar</button>
                    </form>
                </div>
                <div id="feed-posts-container" style="padding: 0 1.5rem 1.5rem; max-height: 70vh; overflow-y: auto;">
                    <?php foreach ($_SESSION['posts'] as $post): ?>
                        <div class="post-card">
                            <div class="post-header">
                                <img src="<?php echo htmlspecialchars($post['authorPic']); ?>" class="post-author-pic">
                                <div><div class="post-author-name"><?php echo htmlspecialchars($post['authorName']); ?></div></div>
                                <div class="post-timestamp"><?php echo htmlspecialchars($post['timestamp']); ?></div>
                            </div>
                            <p><?php echo nl2br(htmlspecialchars($post['text'])); ?></p>
                            <?php if (!empty($post['postImage'])): ?><img src="<?php echo htmlspecialchars($post['postImage']); ?>" class="post-image"><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php break;
        case 'profile_view': 
            $profile = $_SESSION['profile']; ?>
            <div class="glass-container">
                <div class="profile-banner"></div>
                <div class="profile-pic-wrapper"><img src="<?php echo htmlspecialchars($profile['pic']); ?>" class="profile-view-pic"></div>
                <div class="profile-info">
                    <h1 class="profile-view-name"><?php echo htmlspecialchars($profile['fullname']); ?></h1>
                    <div class="profile-meta">
                        <div class="meta-item"><span><?php echo getAge($profile['dob']); ?> anos</span></div>
                         <?php if(!empty($profile['telephone'])): ?><div class="meta-item"><span><?php echo htmlspecialchars($profile['telephone']); ?></span></div><?php endif; ?>
                    </div>
                </div>
                <div class="profile-actions">
                    <a href="index.php?page=create_profile" class="btn">Editar</a>
                    <a href="index.php?page=logout" class="btn" style="background:rgba(239, 68, 68, 0.5);">Logout</a>
                </div>
            </div>
            <?php break;
        case 'contact': ?>
            <div class="glass-container content-padding">
                <h1 class="page-title">Ajuda e Contato</h1>
                <p style="text-align:center; margin-top:-1.5rem; margin-bottom:2rem; opacity:0.8;">Precisa de ajuda? Envie-nos uma mensagem.</p>
                <form action="index.php" method="POST">
                    <input type="hidden" name="action" value="do_contact">
                    <div class="form-group"><label>O seu Nome</label><input type="text" name="contact_name" class="form-control" required value="<?php echo htmlspecialchars($_SESSION['profile']['fullname'] ?? ''); ?>"></div>
                    <div class="form-group"><label>O seu Email</label><input type="email" name="contact_email" class="form-control" required value="<?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?>"></div>
                    <div class="form-group"><label>Mensagem</label><textarea name="contact_message" class="form-control" rows="5" required></textarea></div>
                    <p class="error-message" style="text-align:center; margin-bottom:1rem;"><?php echo $_SESSION['errors']['contact'] ?? ''; ?></p>
                    <button type="submit" class="btn">Enviar Mensagem</button>
                </form>
            </div>
            <?php break;
        case 'contact_success': 
            $submission = $_SESSION['contact_submission'] ?? null; ?>
            <div class="glass-container content-padding">
                <h1 class="page-title">Mensagem Enviada!</h1>
                <p style="text-align:center; margin-top:-1.5rem; margin-bottom:2rem; opacity:0.8;">Obrigado. A sua mensagem foi recebida:</p>
                <?php if ($submission): ?>
                <div class="confirmation-box">
                    <p><strong>Nome:</strong><br><?php echo $submission['name']; ?></p>
                    <p><strong>Email:</strong><br><?php echo $submission['email']; ?></p>
                    <p><strong>Mensagem:</strong><br><?php echo nl2br($submission['message']); ?></p>
                </div>
                <?php endif; ?>
                <a href="index.php?page=feed" class="btn" style="margin-top: 2rem;">Voltar ao Feed</a>
            </div>
            <?php unset($_SESSION['contact_submission']); break;
        default: // 'landing' page ?>
             <div class="glass-container content-padding">
                <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">Conecte-se.</h1>
                <h1 style="font-size: 2.5rem; margin-bottom: 2rem;">Compartilhe. Descubra.</h1>
                <a href="index.php?page=login" class="btn landing-btn">Entrar ou Criar Conta</a>
                <p class="credits">Feito por: Vitor Rafael</p>
            </div>
            <?php break;
    }
    unset($_SESSION['errors']); unset($_SESSION['old_data']);
    ?>
    </div>
</body>
</html>