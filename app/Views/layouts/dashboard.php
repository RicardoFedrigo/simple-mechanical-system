<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Dashboard' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/app.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="d-flex min-vh-100">
    <aside class="sidebar bg-dark text-white p-3">
      <h4 class="mb-4">Workshop ERP</h4>
      <nav class="nav flex-column gap-1">
        <a class="nav-link text-white" href="/dashboard">Dashboard</a>
        <a class="nav-link text-white" href="/customers">Customers</a>
        <a class="nav-link text-white" href="/orders">Orders</a>
        <a class="nav-link text-white" href="/reports">Reports</a>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'Admin'): ?>
          <a class="nav-link text-white" href="/admin/dashboard">Admin</a>
        <?php endif; ?>
      </nav>
      <form class="mt-auto" method="post" action="/logout">
        <button class="btn btn-outline-light btn-sm w-100">Logout</button>
      </form>
    </aside>
    <main class="flex-grow-1 p-4">
      <header class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h2 class="h4 mb-1"><?= $title ?? 'Dashboard' ?></h2>
          <p class="text-muted mb-0">Welcome back, <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Operator') ?>.</p>
        </div>
        <span class="badge bg-success">Role: <?= htmlspecialchars($_SESSION['user']['role'] ?? 'Guest') ?></span>
      </header>
      <?= $content ?? '' ?>
    </main>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>