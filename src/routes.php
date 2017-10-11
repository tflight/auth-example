<?php
// Routes

$app->get('/', function ($request, $response, $args) {
    return $this->renderer->render($response, 'index.phtml', $args);
})->setName('home');

$app->post('/login', function ($request, $response, $args) {
    // Check if the user's credentials are valid, then log them in however you keep track of users.
    // Obviously this method isn't secure, and is just used to illustrate the flow.
    if ($request->getParsedBodyParam('name') === 'user') {
        $_SESSION['isLoggedIn'] = 'yes';
        session_regenerate_id();
        // Login success, redirect to the dashboard.
        return $response->withRedirect($this->router->pathFor('dashboard'));
    }
    // Login failed, redirect home.
    return $response->withRedirect($this->router->pathFor('home'), 403);
});

$app->get('/dashboard', function ($request, $response, $args) {
    // The user asked for the dashboard, give it to them, but add our Auth middleware to validate.
    return $this->renderer->render($response, 'dashboard.phtml', $args);
})->setName('dashboard')->add('Auth');

$app->get('/logout', function ($request, $response, $args) {
    unset($_SESSION['isLoggedIn']);
    session_regenerate_id();
    
    // You wouldn't typically redirect to the dashboard, just doing it to prove we are logged out!
    // After redirecting to the dashboard, the middleware will detect the user is not logged in
    // and then redirect to 'home'
    return $response->withRedirect($this->router->pathFor('dashboard'));
});
