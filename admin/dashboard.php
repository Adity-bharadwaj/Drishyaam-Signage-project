<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$dataDir = __DIR__ . '/../data';

function readJSON($path) {
    if (!file_exists($path)) return null;
    $content = file_get_contents($path);
    return json_decode($content, true);
}

$hero = readJSON($dataDir . '/hero.json');
$products = readJSON($dataDir . '/products.json') ?? [];
$feed = readJSON($dataDir . '/feed.json') ?? [];
$contact = readJSON($dataDir . '/contact.json');

$totalProducts = count($products);
$totalFeed = count($feed);
$contactSocialCount = isset($contact['social']) ? count($contact['social']) : 0;
$heroChipsCount = isset($hero['chips']) ? count($hero['chips']) : 0;
$heroSliderCount = isset($hero['slider_images']) ? count($hero['slider_images']) : 0;
$heroStatsCount = isset($hero['stats']) ? count($hero['stats']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Admin Dashboard — Drishyaam Signage</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --bg: #060e25;
  --bg2: #0b1f4d;
  --bg3: #0f2a6a;
  --accent: #ff7a00;
  --accent-light: #ff9a3c;
  --accent-dark: #d96500;
  --text: #e0e4ef;
  --text-muted: #8892b0;
  --text-dark: #0b1120;
  --card-bg: rgba(255,255,255,.05);
  --card-border: rgba(255,255,255,.1);
  --hover-bg: rgba(255,122,0,.12);
  --danger: #ef4444;
  --success: #22c55e;
  --sidebar: 260px;
  --header-h: 60px;
  --radius: 10px;
}
*{margin:0;padding:0;box-sizing:border-box}
html,body{font-family:'Poppins',sans-serif;background:var(--bg);color:var(--text);height:100%;overflow:hidden}
a{text-decoration:none;color:inherit}
button{cursor:pointer;border:none;background:none;font-family:inherit;font-size:inherit}
input,textarea,select{font-family:inherit;font-size:.875rem}
::-webkit-scrollbar{width:6px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:rgba(255,255,255,.15);border-radius:3px}
::-webkit-scrollbar-thumb:hover{background:rgba(255,255,255,.25)}

/* Sidebar */
.sidebar{
  position:fixed;top:0;left:0;width:var(--sidebar);height:100%;background:var(--bg2);
  border-right:1px solid rgba(255,255,255,.06);z-index:1000;
  display:flex;flex-direction:column;transition:transform .3s ease;
}
.sidebar-brand{
  height:var(--header-h);display:flex;align-items:center;gap:.6rem;
  padding:0 1.2rem;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;
}
.sidebar-brand img{height:34px;width:auto}
.sidebar-brand span{font-weight:700;font-size:.95rem;color:var(--accent);white-space:nowrap}
.sidebar-nav{flex:1;overflow-y:auto;padding:.6rem 0}
.nav-section-title{
  font-size:.65rem;font-weight:600;text-transform:uppercase;letter-spacing:1.2px;
  color:var(--text-muted);padding:.6rem 1.2rem .3rem;
}
.nav-item{
  display:flex;align-items:center;gap:.7rem;padding:.55rem 1.2rem;
  color:var(--text-muted);font-size:.82rem;font-weight:500;transition:all .2s;
  border-left:3px solid transparent;cursor:pointer;
}
.nav-item i{width:18px;text-align:center;font-size:.95rem;flex-shrink:0}
.nav-item:hover{background:rgba(255,255,255,.04);color:var(--text)}
.nav-item.active{background:var(--hover-bg);color:var(--accent);border-left-color:var(--accent)}
.nav-item.logout{margin-top:auto;border-top:1px solid rgba(255,255,255,.06);padding-top:.75rem}
.nav-item.logout:hover{color:var(--danger)}
.nav-item.view-site-link{color:var(--accent);font-weight:600}

/* Main */
.main{
  margin-left:var(--sidebar);height:100%;display:flex;flex-direction:column;
}
.header{
  height:var(--header-h);background:var(--bg2);border-bottom:1px solid rgba(255,255,255,.06);
  display:flex;align-items:center;padding:0 1.5rem;gap:1rem;flex-shrink:0;
}
.hamburger-btn{display:none;padding:.4rem;color:var(--text);font-size:1.2rem}
.header-title{font-size:1rem;font-weight:600;color:var(--text)}
.header-right{margin-left:auto;display:flex;align-items:center;gap:.8rem}
.admin-avatar{
  width:34px;height:34px;border-radius:50%;background:var(--accent);
  display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:#fff;
}
.content{flex:1;overflow-y:auto;padding:1.5rem}
.section{display:none}
.section.active{display:block}
.section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:.75rem}
.section-header h2{font-size:1.3rem;font-weight:700}
.section-header h2 i{color:var(--accent);margin-right:.5rem}
.btn{
  display:inline-flex;align-items:center;gap:.4rem;padding:.5rem 1.1rem;
  border-radius:var(--radius);font-size:.82rem;font-weight:600;transition:all .2s;
}
.btn-primary{background:var(--accent);color:#fff}
.btn-primary:hover{background:var(--accent-light);transform:translateY(-1px)}
.btn-danger{background:var(--danger);color:#fff}
.btn-danger:hover{opacity:.85}
.btn-success{background:var(--success);color:#fff}
.btn-success:hover{opacity:.85}
.btn-outline{background:transparent;border:1.5px solid var(--card-border);color:var(--text)}
.btn-outline:hover{border-color:var(--accent);color:var(--accent)}
.btn-sm{padding:.35rem .75rem;font-size:.75rem}

/* Cards */
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem}
.stat-card{
  background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius);
  padding:1.2rem 1.3rem;transition:all .2s;
}
.stat-card:hover{border-color:rgba(255,122,0,.25);transform:translateY(-2px)}
.stat-card .stat-icon{font-size:1.6rem;color:var(--accent);margin-bottom:.4rem}
.stat-card .stat-num{font-size:1.8rem;font-weight:800;line-height:1.2}
.stat-card .stat-label{font-size:.75rem;color:var(--text-muted)}

/* Tables */
.table-wrap{overflow-x:auto;border-radius:var(--radius);border:1px solid var(--card-border)}
table{width:100%;border-collapse:collapse;font-size:.82rem}
thead{background:rgba(255,255,255,.04)}
th{padding:.75rem .85rem;text-align:left;font-weight:600;color:var(--text-muted);font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;white-space:nowrap}
td{padding:.7rem .85rem;border-top:1px solid rgba(255,255,255,.05);vertical-align:middle}
tbody tr:hover{background:rgba(255,255,255,.03)}
td img{width:48px;height:36px;object-fit:cover;border-radius:4px}
td .actions{display:flex;gap:.35rem}
.badge{
  display:inline-block;font-size:.65rem;font-weight:600;padding:.15rem .5rem;
  border-radius:20px;background:rgba(255,122,0,.15);color:var(--accent);
}

/* Forms */
.form-group{margin-bottom:1rem}
.form-group label{display:block;font-size:.78rem;font-weight:600;color:var(--text-muted);margin-bottom:.3rem}
.form-group input,.form-group textarea,.form-group select{
  width:100%;padding:.6rem .8rem;background:rgba(255,255,255,.06);border:1.5px solid rgba(255,255,255,.1);
  border-radius:6px;color:var(--text);outline:none;transition:border .2s;
}
.form-group input:focus,.form-group textarea:focus,.form-group select:focus{border-color:var(--accent)}
.form-group textarea{resize:vertical;min-height:80px}
.form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.form-actions{display:flex;gap:.75rem;margin-top:1rem}

/* Modal */
.modal-overlay{
  position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:2000;
  display:none;align-items:center;justify-content:center;backdrop-filter:blur(4px);
}
.modal-overlay.open{display:flex}
.modal{
  background:var(--bg2);border:1px solid rgba(255,255,255,.1);border-radius:var(--radius);
  width:90%;max-width:560px;max-height:90vh;overflow-y:auto;padding:1.5rem;
}
.modal-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem}
.modal-header h3{font-size:1.05rem;font-weight:700}
.modal-close{font-size:1.3rem;color:var(--text-muted);padding:.2rem}
.modal-close:hover{color:var(--danger)}

/* Toast */
.toast-container{position:fixed;top:1rem;right:1rem;z-index:3000;display:flex;flex-direction:column;gap:.5rem}
.toast{
  padding:.75rem 1.1rem;border-radius:var(--radius);font-size:.82rem;font-weight:500;
  display:flex;align-items:center;gap:.6rem;box-shadow:0 8px 30px rgba(0,0,0,.4);
  animation: slideIn .25s ease;min-width:260px;
}
.toast.success{background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.3);color:#4ade80}
.toast.error{background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.3);color:#f87171}
@keyframes slideIn{from{opacity:0;transform:translateX(60px)}to{opacity:1;transform:none}}

/* Upload */
.upload-zone{
  border:2px dashed rgba(255,255,255,.15);border-radius:var(--radius);padding:3rem 2rem;
  text-align:center;cursor:pointer;transition:all .2s;margin-bottom:1.5rem;
}
.upload-zone:hover,.upload-zone.dragover{border-color:var(--accent);background:rgba(255,122,0,.05)}
.upload-zone i{font-size:3rem;color:var(--text-muted);margin-bottom:.5rem}
.upload-zone p{color:var(--text-muted);font-size:.85rem}
.upload-zone .highlight{color:var(--accent);font-weight:600}
.uploaded-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:.85rem}
.uploaded-item{
  background:var(--card-bg);border:1px solid var(--card-border);border-radius:var(--radius);
  overflow:hidden;position:relative;
}
.uploaded-item img{width:100%;height:120px;object-fit:cover;display:block}
.uploaded-item .url-bar{
  padding:.4rem .6rem;font-size:.65rem;color:var(--text-muted);white-space:nowrap;
  overflow:hidden;text-overflow:ellipsis;background:rgba(0,0,0,.3);cursor:pointer;
}
.uploaded-item .url-bar:hover{color:var(--accent)}

/* Contact */
.contact-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.social-field-row{display:flex;gap:.5rem;align-items:center}
.social-field-row i{font-size:1.1rem;color:var(--accent);width:20px;text-align:center}
.social-field-row input{flex:1}

/* Hero chips */
.chips-edit-area{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.4rem}
.chip-tag{
  display:flex;align-items:center;gap:.35rem;background:rgba(255,122,0,.12);
  border:1px solid rgba(255,122,0,.25);color:var(--accent);padding:.25rem .6rem;
  border-radius:20px;font-size:.75rem;
}
.chip-tag .remove-chip{cursor:pointer;font-size:.6rem;color:var(--text-muted)}
.chip-tag .remove-chip:hover{color:var(--danger)}

/* Responsive */
@media(max-width:768px){
  .sidebar{transform:translateX(-100%)}
  .sidebar.open{transform:none}
  .main{margin-left:0}
  .hamburger-btn{display:block}
  .form-row-2,.contact-form-grid{grid-template-columns:1fr}
  .stats-grid{grid-template-columns:repeat(auto-fit,minmax(140px,1fr))}
  .modal{width:95%;padding:1rem}
  .content{padding:1rem}
}
</style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <img src="../logo.png" alt="Drishyaam" onerror="this.style.display='none'">
    <span>Admin Panel</span>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-section-title">Main</div>
    <div class="nav-item active" data-tab="dashboard"><i class="fas fa-house"></i> Dashboard</div>
    <div class="nav-item" data-tab="hero"><i class="fas fa-sliders"></i> Hero Section</div>
    <div class="nav-item" data-tab="products"><i class="fas fa-box"></i> Products</div>
    <div class="nav-item" data-tab="feed"><i class="fas fa-rss"></i> Feed Posts</div>
    <div class="nav-item" data-tab="contact"><i class="fas fa-envelope"></i> Contact</div>
    <div class="nav-item" data-tab="upload"><i class="fas fa-image"></i> Upload Images</div>
    <div class="nav-section-title">Links</div>
    <a href="../index.html" target="_blank" class="nav-item view-site-link"><i class="fas fa-arrow-up-right-from-square"></i> View Site</a>
    <a href="logout.php" class="nav-item logout"><i class="fas fa-arrow-right-from-bracket"></i> Logout</a>
  </nav>
</aside>

<!-- Main -->
<div class="main">
  <header class="header">
    <button class="hamburger-btn" id="hamburgerBtn"><i class="fas fa-bars"></i></button>
    <span class="header-title" id="headerTitle">Dashboard</span>
    <div class="header-right">
      <span style="font-size:.78rem;color:var(--text-muted)"><?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?></span>
      <div class="admin-avatar"><?= strtoupper(($_SESSION['admin_username'] ?? 'A')[0]) ?></div>
    </div>
  </header>

  <div class="content" id="mainContent">

    <!-- ═══ DASHBOARD ═══ -->
    <div class="section active" id="tab-dashboard">
      <div class="section-header">
        <h2><i class="fas fa-house"></i> Dashboard Overview</h2>
      </div>
      <div class="stats-grid">
        <div class="stat-card"><div class="stat-icon"><i class="fas fa-box"></i></div><div class="stat-num"><?= $totalProducts ?></div><div class="stat-label">Total Products</div></div>
        <div class="stat-card"><div class="stat-icon"><i class="fas fa-rss"></i></div><div class="stat-num"><?= $totalFeed ?></div><div class="stat-label">Feed Posts</div></div>
        <div class="stat-card"><div class="stat-icon"><i class="fas fa-tags"></i></div><div class="stat-num"><?= $heroChipsCount ?></div><div class="stat-label">Hero Chips</div></div>
        <div class="stat-card"><div class="stat-icon"><i class="fas fa-images"></i></div><div class="stat-num"><?= $heroSliderCount ?></div><div class="stat-label">Slider Images</div></div>
        <div class="stat-card"><div class="stat-icon"><i class="fas fa-chart-simple"></i></div><div class="stat-num"><?= $heroStatsCount ?></div><div class="stat-label">Hero Stats</div></div>
        <div class="stat-card"><div class="stat-icon"><i class="fas fa-share-nodes"></i></div><div class="stat-num"><?= $contactSocialCount ?></div><div class="stat-label">Social Links</div></div>
      </div>
    </div>

    <!-- ═══ HERO ═══ -->
    <div class="section" id="tab-hero">
      <div class="section-header">
        <h2><i class="fas fa-sliders"></i> Hero Section</h2>
      </div>
      <form id="heroForm" style="display:grid;gap:1rem;max-width:800px">
        <div class="form-group">
          <label>Badge Text</label>
          <input type="text" name="badge" value="<?= htmlspecialchars($hero['badge'] ?? '') ?>">
        </div>
        <div class="form-row-2">
          <div class="form-group">
            <label>Title Line 1</label>
            <input type="text" name="title_line1" value="<?= htmlspecialchars($hero['title_line1'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Title Line 2</label>
            <input type="text" name="title_line2" value="<?= htmlspecialchars($hero['title_line2'] ?? '') ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Subtitle</label>
          <textarea name="subtitle" rows="3"><?= htmlspecialchars($hero['subtitle'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
          <label>Button 1 Text</label>
          <input type="text" name="btn1_text" value="<?= htmlspecialchars($hero['btn1_text'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Button 1 Link</label>
          <input type="text" name="btn1_link" value="<?= htmlspecialchars($hero['btn1_link'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Button 2 Text</label>
          <input type="text" name="btn2_text" value="<?= htmlspecialchars($hero['btn2_text'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Button 2 Link</label>
          <input type="text" name="btn2_link" value="<?= htmlspecialchars($hero['btn2_link'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Background Image</label>
          <div style="display:flex;gap:.5rem">
            <input type="url" name="bg_image" id="heroBgImage" value="<?= htmlspecialchars($hero['bg_image'] ?? '') ?>" style="flex:1">
            <button type="button" class="btn btn-sm btn-outline upload-btn" data-target="heroBgImage"><i class="fas fa-upload"></i> Upload</button>
            <button type="button" class="btn btn-sm btn-outline browse-btn" data-target="heroBgImage"><i class="fas fa-folder-open"></i> Browse</button>
          </div>
          <div id="heroBgPreview" style="margin-top:.4rem">
            <?php if (!empty($hero['bg_image'])):
              $bgSrc = (strpos($hero['bg_image'], 'http') === 0) ? $hero['bg_image'] : '../' . $hero['bg_image'];
            ?>
            <img src="<?= htmlspecialchars($bgSrc) ?>" alt="" style="max-height:80px;border-radius:4px" onerror="this.style.display='none'">
            <?php endif; ?>
          </div>
        </div>
        <div class="form-group">
          <label>Slider Images</label>
          <div id="sliderImagesContainer">
            <?php if (!empty($hero['slider_images'])): foreach ($hero['slider_images'] as $idx => $si):
              $src = (strpos($si, 'http') === 0) ? $si : '../' . $si;
            ?>
            <div class="slider-img-row" data-index="<?= $idx ?>" style="display:flex;gap:.4rem;margin-bottom:.4rem;align-items:center">
              <img src="<?= htmlspecialchars($src) ?>" alt="" style="width:48px;height:36px;object-fit:cover;border-radius:4px" onerror="this.style.display='none'">
              <input type="url" class="slider-img-input" value="<?= htmlspecialchars($si) ?>" style="flex:1;padding:.4rem .6rem;background:rgba(255,255,255,.06);border:1.5px solid rgba(255,255,255,.1);border-radius:6px;color:var(--text);font-size:.78rem">
              <button type="button" class="btn btn-sm btn-danger remove-slider-img"><i class="fas fa-times"></i></button>
            </div>
            <?php endforeach; endif; ?>
          </div>
          <div style="display:flex;gap:.4rem;margin-top:.4rem;flex-wrap:wrap">
            <button type="button" class="btn btn-sm btn-outline" id="addSliderImageBtn"><i class="fas fa-plus"></i> Add URL</button>
            <button type="button" class="btn btn-sm btn-outline upload-slider-btn"><i class="fas fa-upload"></i> Upload &amp; Add</button>
            <button type="button" class="btn btn-sm btn-outline browse-slider-btn"><i class="fas fa-folder-open"></i> Browse &amp; Add</button>
          </div>
        </div>
        <div class="form-group">
          <label>Chips (comma separated)</label>
          <input type="text" name="chips" value="<?= htmlspecialchars(implode(', ', $hero['chips'] ?? [])) ?>">
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Save Hero</button>
        </div>
      </form>
    </div>

    <!-- ═══ PRODUCTS ═══ -->
    <div class="section" id="tab-products">
      <div class="section-header">
        <h2><i class="fas fa-box"></i> Products</h2>
        <button class="btn btn-primary" id="addProductBtn"><i class="fas fa-plus"></i> Add Product</button>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Image</th>
              <th>Title</th>
              <th>Category</th>
              <th>Badge</th>
              <th>Order</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="productsTableBody">
            <?php foreach ($products as $p): ?>
            <tr data-id="<?= $p['id'] ?>">
              <td><img src="<?= htmlspecialchars((strpos($p['image'] ?? '', 'http') === 0) ? $p['image'] : '../' . ($p['image'] ?? '')) ?>" alt="" loading="lazy" onerror="this.style.display='none'"></td>
              <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
              <td><?= htmlspecialchars($p['category'] ?? '') ?></td>
              <td><?= $p['badge'] ? '<span class="badge">' . htmlspecialchars($p['badge']) . '</span>' : '' ?></td>
              <td><?= $p['order'] ?? 0 ?></td>
              <td>
                <div class="actions">
                  <button class="btn btn-sm btn-primary edit-product" data-id="<?= $p['id'] ?>"><i class="fas fa-pen"></i></button>
                  <button class="btn btn-sm btn-danger delete-product" data-id="<?= $p['id'] ?>"><i class="fas fa-trash"></i></button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ═══ FEED ═══ -->
    <div class="section" id="tab-feed">
      <div class="section-header">
        <h2><i class="fas fa-rss"></i> Feed Posts</h2>
        <button class="btn btn-primary" id="addFeedBtn"><i class="fas fa-plus"></i> Add Post</button>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Image</th>
              <th>Title</th>
              <th>Description</th>
              <th>Date</th>
              <th>Order</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="feedTableBody">
            <?php foreach ($feed as $f): ?>
            <tr data-id="<?= $f['id'] ?>">
              <td><img src="<?= htmlspecialchars((strpos($f['image'] ?? '', 'http') === 0) ? $f['image'] : '../' . ($f['image'] ?? '')) ?>" alt="" loading="lazy" onerror="this.style.display='none'"></td>
              <td><strong><?= htmlspecialchars($f['title']) ?></strong></td>
              <td><?= htmlspecialchars(mb_substr($f['description'] ?? '', 0, 60)) ?></td>
              <td><?= htmlspecialchars($f['date'] ?? '') ?></td>
              <td><?= $f['order'] ?? 0 ?></td>
              <td>
                <div class="actions">
                  <button class="btn btn-sm btn-primary edit-feed" data-id="<?= $f['id'] ?>"><i class="fas fa-pen"></i></button>
                  <button class="btn btn-sm btn-danger delete-feed" data-id="<?= $f['id'] ?>"><i class="fas fa-trash"></i></button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ═══ CONTACT ═══ -->
    <div class="section" id="tab-contact">
      <div class="section-header">
        <h2><i class="fas fa-envelope"></i> Contact Settings</h2>
      </div>
      <form id="contactForm" style="max-width:700px">
        <div class="contact-form-grid">
          <div class="form-group">
            <label>Primary Phone</label>
            <input type="text" name="phone_primary" value="<?= htmlspecialchars($contact['phone_primary'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Secondary Phone</label>
            <input type="text" name="phone_secondary" value="<?= htmlspecialchars($contact['phone_secondary'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>WhatsApp</label>
            <input type="text" name="whatsapp" value="<?= htmlspecialchars($contact['whatsapp'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($contact['email'] ?? '') ?>">
          </div>
        </div>
        <div class="form-group">
          <label>Address</label>
          <input type="text" name="address" value="<?= htmlspecialchars($contact['address'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Map Embed URL</label>
          <input type="url" name="map_embed" value="<?= htmlspecialchars($contact['map_embed'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Social Links</label>
          <div class="social-field-row"><i class="fab fa-instagram"></i><input type="url" name="social_instagram" placeholder="Instagram URL" value="<?= htmlspecialchars($contact['social']['instagram'] ?? '') ?>"></div>
          <div class="social-field-row" style="margin-top:.4rem"><i class="fab fa-facebook-f"></i><input type="url" name="social_facebook" placeholder="Facebook URL" value="<?= htmlspecialchars($contact['social']['facebook'] ?? '') ?>"></div>
          <div class="social-field-row" style="margin-top:.4rem"><i class="fab fa-linkedin-in"></i><input type="url" name="social_linkedin" placeholder="LinkedIn URL" value="<?= htmlspecialchars($contact['social']['linkedin'] ?? '') ?>"></div>
          <div class="social-field-row" style="margin-top:.4rem"><i class="fab fa-whatsapp"></i><input type="url" name="social_whatsapp" placeholder="WhatsApp URL" value="<?= htmlspecialchars($contact['social']['whatsapp'] ?? '') ?>"></div>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Save Contact</button>
        </div>
      </form>
    </div>

    <!-- ═══ UPLOAD ═══ -->
    <div class="section" id="tab-upload">
      <div class="section-header">
        <h2><i class="fas fa-image"></i> Upload Images</h2>
      </div>
      <div class="upload-zone" id="uploadZone">
        <i class="fas fa-cloud-upload-alt"></i>
        <p>Drag &amp; drop images here or <span class="highlight">click to browse</span></p>
        <p style="font-size:.75rem;margin-top:.3rem">JPG, PNG, WEBP — Max 10MB each</p>
        <input type="file" id="fileInput" accept="image/*" multiple style="display:none">
      </div>
      <div id="uploadProgress" style="display:none;margin-bottom:1rem">
        <div style="height:4px;background:rgba(255,255,255,.1);border-radius:4px;overflow:hidden">
          <div id="progressBar" style="height:100%;width:0%;background:var(--accent);border-radius:4px;transition:width .3s"></div>
        </div>
        <p id="progressText" style="font-size:.78rem;color:var(--text-muted);margin-top:.3rem"></p>
      </div>
      <div class="uploaded-grid" id="uploadedGrid"></div>
    </div>

  </div>
</div>

<!-- Product Modal -->
<div class="modal-overlay" id="productModal">
  <div class="modal">
    <div class="modal-header">
      <h3 id="productModalTitle">Add Product</h3>
      <button class="modal-close" id="productModalClose">&times;</button>
    </div>
    <form id="productForm">
      <input type="hidden" name="id" value="">
      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" required>
      </div>
      <div class="form-group">
        <label>Category</label>
        <input type="text" name="category" required>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3" required></textarea>
      </div>
      <div class="form-group">
        <label>Image</label>
        <div style="display:flex;gap:.5rem">
          <input type="url" name="image" id="productImage" required style="flex:1">
          <button type="button" class="btn btn-sm btn-outline upload-btn" data-target="productImage"><i class="fas fa-upload"></i> Upload</button>
          <button type="button" class="btn btn-sm btn-outline browse-btn" data-target="productImage"><i class="fas fa-folder-open"></i> Browse</button>
        </div>
        <div id="productImagePreview" style="margin-top:.4rem"></div>
      </div>
      <div class="form-group">
        <label>Tags (comma separated)</label>
        <input type="text" name="tags" placeholder="e.g. IP65, HD 1080p">
      </div>
      <div class="form-row-2">
        <div class="form-group">
          <label>Badge</label>
          <input type="text" name="badge" placeholder="e.g. Best Seller">
        </div>
        <div class="form-group">
          <label>Order</label>
          <input type="number" name="order" value="0">
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Save Product</button>
        <button type="button" class="btn btn-outline" id="productModalCancel">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Feed Modal -->
<div class="modal-overlay" id="feedModal">
  <div class="modal">
    <div class="modal-header">
      <h3 id="feedModalTitle">Add Feed Post</h3>
      <button class="modal-close" id="feedModalClose">&times;</button>
    </div>
    <form id="feedForm">
      <input type="hidden" name="id" value="">
      <div class="form-group">
        <label>Title</label>
        <input type="text" name="title" required>
      </div>
      <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3"></textarea>
      </div>
      <div class="form-group">
        <label>Image</label>
        <div style="display:flex;gap:.5rem">
          <input type="url" name="image" id="feedImage" style="flex:1">
          <button type="button" class="btn btn-sm btn-outline upload-btn" data-target="feedImage"><i class="fas fa-upload"></i> Upload</button>
          <button type="button" class="btn btn-sm btn-outline browse-btn" data-target="feedImage"><i class="fas fa-folder-open"></i> Browse</button>
        </div>
        <div id="feedImagePreview" style="margin-top:.4rem"></div>
      </div>
      <div class="form-row-2">
        <div class="form-group">
          <label>Date</label>
          <input type="date" name="date">
        </div>
        <div class="form-group">
          <label>Order</label>
          <input type="number" name="order" value="0">
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Save Post</button>
        <button type="button" class="btn btn-outline" id="feedModalCancel">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Media Browser Modal -->
<div class="modal-overlay" id="mediaBrowserModal">
  <div class="modal" style="max-width:720px">
    <div class="modal-header">
      <h3><i class="fas fa-images"></i> Media Library</h3>
      <button class="modal-close" id="mediaBrowserClose">&times;</button>
    </div>
    <div style="margin-bottom:1rem">
      <div class="upload-zone" id="mediaUploadZone" style="padding:1rem;margin-bottom:0">
        <i class="fas fa-cloud-upload-alt" style="font-size:1.5rem"></i>
        <p style="font-size:.8rem">Drag &amp; drop or <span class="highlight">click to upload</span></p>
        <input type="file" id="mediaFileInput" accept="image/*" style="display:none">
      </div>
    </div>
    <div id="mediaBrowserGrid" class="uploaded-grid" style="max-height:400px;overflow-y:auto"></div>
    <div style="text-align:center;margin-top:.75rem">
      <button type="button" class="btn btn-outline" id="mediaBrowserCancel">Cancel</button>
    </div>
  </div>
</div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<script>
const SIDEBAR = document.getElementById('sidebar');
const HAMBURGER = document.getElementById('hamburgerBtn');
const NAV_ITEMS = document.querySelectorAll('.nav-item[data-tab]');
const SECTIONS = {
  dashboard: document.getElementById('tab-dashboard'),
  hero: document.getElementById('tab-hero'),
  products: document.getElementById('tab-products'),
  feed: document.getElementById('tab-feed'),
  contact: document.getElementById('tab-contact'),
  upload: document.getElementById('tab-upload'),
};
const HEADER_TITLE = document.getElementById('headerTitle');

HAMBURGER.addEventListener('click', () => SIDEBAR.classList.toggle('open'));

NAV_ITEMS.forEach(item => {
  item.addEventListener('click', () => {
    const tab = item.dataset.tab;
    NAV_ITEMS.forEach(n => n.classList.remove('active'));
    item.classList.add('active');
    Object.keys(SECTIONS).forEach(key => {
      SECTIONS[key].classList.toggle('active', key === tab);
    });
    HEADER_TITLE.textContent = item.textContent.trim();
    SIDEBAR.classList.remove('open');
  });
});

document.addEventListener('click', (e) => {
  if (window.innerWidth <= 768 && !SIDEBAR.contains(e.target) && !HAMBURGER.contains(e.target)) {
    SIDEBAR.classList.remove('open');
  }
});

/* ── TOAST ── */
function showToast(message, type = 'success') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = 'toast ' + type;
  const icon = type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark';
  toast.innerHTML = '<i class="fas ' + icon + '"></i> ' + message;
  container.appendChild(toast);
  setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity .3s'; setTimeout(() => toast.remove(), 300); }, 3000);
}

/* ── FETCH HELPERS ── */
async function apiPost(url, data) {
  const res = await fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  return res.json();
}

/* ── PRODUCTS ── */
function productRow(p) {
  const badgeHtml = p.badge ? '<span class="badge">' + p.badge + '</span>' : '';
  const imgSrc = p.image ? (p.image.indexOf('http') === 0 ? p.image : '../' + p.image) : '';
  const imgHtml = p.image ? '<img src="' + imgSrc + '" alt="" loading="lazy" onerror="this.style.display=\'none\'">' : '<span style="color:var(--text-muted);font-size:.7rem">No img</span>';
  return '<tr data-id="' + p.id + '"><td>' + imgHtml + '</td><td><strong>' + p.title + '</strong></td><td>' + (p.category || '') + '</td><td>' + badgeHtml + '</td><td>' + (p.order || 0) + '</td><td><div class="actions"><button class="btn btn-sm btn-primary edit-product" data-id="' + p.id + '"><i class="fas fa-pen"></i></button><button class="btn btn-sm btn-danger delete-product" data-id="' + p.id + '"><i class="fas fa-trash"></i></button></div></td></tr>';
}

function renderProducts(products) {
  const tbody = document.getElementById('productsTableBody');
  tbody.innerHTML = products.map(productRow).join('');
}

document.getElementById('addProductBtn').addEventListener('click', () => openProductModal());
document.getElementById('productModalClose').addEventListener('click', () => closeModal('productModal'));
document.getElementById('productModalCancel').addEventListener('click', () => closeModal('productModal'));

document.getElementById('productForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const data = {};
  fd.forEach((v, k) => { data[k] = v; });
  if (data.id) data.id = parseInt(data.id);
  if (data.order) data.order = parseInt(data.order);
  if (data.tags) data.tags = data.tags.split(',').map(s => s.trim()).filter(Boolean);
  else data.tags = [];
  const res = await apiPost('save-products.php', data);
  if (res.success) {
    showToast(res.message || 'Product saved');
    closeModal('productModal');
    if (res.products) renderProducts(res.products);
  } else {
    showToast(res.message || 'Failed to save product', 'error');
  }
});

document.addEventListener('click', async (e) => {
  const editBtn = e.target.closest('.edit-product');
  if (editBtn) {
    const id = parseInt(editBtn.dataset.id);
    const row = document.querySelector('#productsTableBody tr[data-id="' + id + '"]');
    if (!row) return;
    const cells = row.querySelectorAll('td');
    const form = document.getElementById('productForm');
    form.querySelector('[name="id"]').value = id;
    form.querySelector('[name="title"]').value = cells[1].textContent.trim();
    form.querySelector('[name="category"]').value = cells[2].textContent.trim();
    form.querySelector('[name="image"]').value = cells[0].querySelector('img')?.src || '';
    form.querySelector('[name="order"]').value = cells[4].textContent.trim();
    const badgeEl = cells[3].querySelector('.badge');
    form.querySelector('[name="badge"]').value = badgeEl ? badgeEl.textContent.trim() : '';
    try {
      const resp = await fetch('save-products.php?get=' + id);
      const data = await resp.json();
      if (data.product) {
        form.querySelector('[name="description"]').value = data.product.description || '';
        form.querySelector('[name="tags"]').value = (data.product.tags || []).join(', ');
      }
    } catch(_) {}
    openProductModal('Edit Product');
  }
});

document.addEventListener('click', async (e) => {
  const delBtn = e.target.closest('.delete-product');
  if (delBtn && confirm('Delete this product?')) {
    const id = parseInt(delBtn.dataset.id);
    const res = await apiPost('save-products.php', { id: id, delete: true });
    if (res.success) {
      showToast(res.message || 'Product deleted');
      if (res.products) renderProducts(res.products);
    } else {
      showToast(res.message || 'Failed to delete', 'error');
    }
  }
});

function openProductModal(title) {
  if (!title) title = 'Add Product';
  document.getElementById('productModalTitle').textContent = title;
  if (title === 'Add Product') document.getElementById('productForm').reset();
  document.getElementById('productModal').classList.add('open');
}
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

/* ── FEED ── */
function feedRow(f) {
  const imgSrc = f.image ? (f.image.indexOf('http') === 0 ? f.image : '../' + f.image) : '';
  const imgHtml = f.image ? '<img src="' + imgSrc + '" alt="" loading="lazy" onerror="this.style.display=\'none\'">' : '<span style="color:var(--text-muted);font-size:.7rem">No img</span>';
  return '<tr data-id="' + f.id + '"><td>' + imgHtml + '</td><td><strong>' + f.title + '</strong></td><td>' + (f.description ? f.description.substring(0, 60) : '') + '</td><td>' + (f.date || '') + '</td><td>' + (f.order || 0) + '</td><td><div class="actions"><button class="btn btn-sm btn-primary edit-feed" data-id="' + f.id + '"><i class="fas fa-pen"></i></button><button class="btn btn-sm btn-danger delete-feed" data-id="' + f.id + '"><i class="fas fa-trash"></i></button></div></td></tr>';
}

function renderFeed(feed) {
  document.getElementById('feedTableBody').innerHTML = feed.map(feedRow).join('');
}

document.getElementById('addFeedBtn').addEventListener('click', () => openFeedModal());
document.getElementById('feedModalClose').addEventListener('click', () => closeModal('feedModal'));
document.getElementById('feedModalCancel').addEventListener('click', () => closeModal('feedModal'));

document.getElementById('feedForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const data = {};
  fd.forEach((v, k) => { data[k] = v; });
  if (data.id) data.id = parseInt(data.id);
  if (data.order) data.order = parseInt(data.order);
  const res = await apiPost('save-feed.php', data);
  if (res.success) {
    showToast(res.message || 'Feed post saved');
    closeModal('feedModal');
    if (res.feed) renderFeed(res.feed);
  } else {
    showToast(res.message || 'Failed to save feed post', 'error');
  }
});

document.addEventListener('click', async (e) => {
  const editBtn = e.target.closest('.edit-feed');
  if (editBtn) {
    const id = parseInt(editBtn.dataset.id);
    const row = document.querySelector('#feedTableBody tr[data-id="' + id + '"]');
    if (!row) return;
    const cells = row.querySelectorAll('td');
    const form = document.getElementById('feedForm');
    form.querySelector('[name="id"]').value = id;
    form.querySelector('[name="title"]').value = cells[1].textContent.trim();
    form.querySelector('[name="image"]').value = cells[0].querySelector('img')?.src || '';
    form.querySelector('[name="date"]').value = cells[3].textContent.trim();
    form.querySelector('[name="order"]').value = cells[4].textContent.trim();
    const descFull = cells[2].textContent.trim();
    try {
      const resp = await fetch('save-feed.php?get=' + id);
      const data = await resp.json();
      if (data.feed) {
        form.querySelector('[name="description"]').value = data.feed.description || '';
      } else {
        form.querySelector('[name="description"]').value = descFull;
      }
    } catch(_) {
      form.querySelector('[name="description"]').value = descFull;
    }
    openFeedModal('Edit Feed Post');
  }
});

document.addEventListener('click', async (e) => {
  const delBtn = e.target.closest('.delete-feed');
  if (delBtn && confirm('Delete this feed post?')) {
    const id = parseInt(delBtn.dataset.id);
    const res = await apiPost('save-feed.php', { id: id, delete: true });
    if (res.success) {
      showToast(res.message || 'Feed post deleted');
      if (res.feed) renderFeed(res.feed);
    } else {
      showToast(res.message || 'Failed to delete', 'error');
    }
  }
});

function openFeedModal(title) {
  if (!title) title = 'Add Feed Post';
  document.getElementById('feedModalTitle').textContent = title;
  if (title === 'Add Feed Post') document.getElementById('feedForm').reset();
  document.getElementById('feedModal').classList.add('open');
}

/* ── CONTACT ── */
document.getElementById('contactForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const data = {
    phone_primary: fd.get('phone_primary') || '',
    phone_secondary: fd.get('phone_secondary') || '',
    whatsapp: fd.get('whatsapp') || '',
    email: fd.get('email') || '',
    address: fd.get('address') || '',
    map_embed: fd.get('map_embed') || '',
    social: {
      instagram: fd.get('social_instagram') || '',
      facebook: fd.get('social_facebook') || '',
      linkedin: fd.get('social_linkedin') || '',
      whatsapp: fd.get('social_whatsapp') || '',
    }
  };
  const res = await apiPost('save-contact.php', data);
  if (res.success) showToast(res.message || 'Contact saved successfully');
  else showToast(res.message || 'Failed to save contact', 'error');
});

/* ── UPLOAD ── */
const UPLOAD_ZONE = document.getElementById('uploadZone');
const FILE_INPUT = document.getElementById('fileInput');

UPLOAD_ZONE.addEventListener('click', () => FILE_INPUT.click());

UPLOAD_ZONE.addEventListener('dragover', (e) => {
  e.preventDefault();
  UPLOAD_ZONE.classList.add('dragover');
});
UPLOAD_ZONE.addEventListener('dragleave', () => {
  UPLOAD_ZONE.classList.remove('dragover');
});
UPLOAD_ZONE.addEventListener('drop', (e) => {
  e.preventDefault();
  UPLOAD_ZONE.classList.remove('dragover');
  if (e.dataTransfer.files.length) uploadFiles(e.dataTransfer.files);
});

FILE_INPUT.addEventListener('change', () => {
  if (FILE_INPUT.files.length) uploadFiles(FILE_INPUT.files);
});

async function uploadFiles(files) {
  const progressDiv = document.getElementById('uploadProgress');
  const progressBar = document.getElementById('progressBar');
  const progressText = document.getElementById('progressText');
  progressDiv.style.display = 'block';

  for (let i = 0; i < files.length; i++) {
    const file = files[i];
    if (file.size > 10 * 1024 * 1024) {
      showToast(file.name + ' exceeds 10MB limit', 'error');
      continue;
    }
    const percent = Math.round(((i) / files.length) * 100);
    progressBar.style.width = percent + '%';
    progressText.textContent = 'Uploading ' + (i + 1) + ' of ' + files.length + '...';

    const formData = new FormData();
    formData.append('file', file);

    try {
      const res = await fetch('upload.php', { method: 'POST', body: formData });
      const json = await res.json();
      if (json.success) {
        showToast(file.name + ' uploaded');
        addUploadedImage(json.url || json.path);
      } else {
        showToast(json.message || file.name + ' upload failed', 'error');
      }
    } catch (err) {
      showToast(file.name + ' upload error', 'error');
    }
  }

  progressBar.style.width = '100%';
  progressText.textContent = 'Upload complete';
  setTimeout(() => { progressDiv.style.display = 'none'; progressBar.style.width = '0%'; }, 2000);
  FILE_INPUT.value = '';
}

function addUploadedImage(url) {
  const grid = document.getElementById('uploadedGrid');
  const div = document.createElement('div');
  div.className = 'uploaded-item';
  const imgSrc = url.indexOf('http') === 0 ? url : '../' + url;
  div.innerHTML = '<img src="' + imgSrc + '" alt="" loading="lazy"><div class="url-bar" title="Click to copy">' + url + '</div>';
  div.querySelector('.url-bar').addEventListener('click', () => {
    navigator.clipboard.writeText(url).then(() => showToast('URL copied')).catch(() => {});
  });
  grid.prepend(div);
}

/* ── Initial load: fetch uploaded images ── */
(async function loadUploaded() {
  try {
    const res = await fetch('upload.php?list=1');
    const data = await res.json();
    if (data.images && Array.isArray(data.images)) {
      const grid = document.getElementById('uploadedGrid');
      grid.innerHTML = '';
      data.images.forEach(url => addUploadedImage(url));
    }
  } catch(_) {}
})();

/* ═══════════════ IMAGE UPLOAD & MEDIA BROWSER ═══════════════ */

// Track which input field is the active target for media browser
let activeMediaTarget = null;

// Function to upload a file and return the URL
async function uploadFileAndGetUrl(file) {
  if (file.size > 10 * 1024 * 1024) {
    showToast(file.name + ' exceeds 10MB limit', 'error');
    return null;
  }
  const formData = new FormData();
  formData.append('file', file);
  try {
    const res = await fetch('upload.php', { method: 'POST', body: formData });
    const json = await res.json();
    if (json.success) {
      addUploadedImage(json.url || json.path);
      return json.url || json.path;
    } else {
      showToast(json.message || 'Upload failed', 'error');
      return null;
    }
  } catch (err) {
    showToast('Upload error', 'error');
    return null;
  }
}

// ── Upload buttons: open file picker, upload, fill input ──
document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.upload-btn');
  if (!btn) return;
  const targetId = btn.dataset.target;
  if (!targetId) return;

  // Create temporary file input
  const fileInput = document.createElement('input');
  fileInput.type = 'file';
  fileInput.accept = 'image/*';

  fileInput.addEventListener('change', async () => {
    if (!fileInput.files.length) return;
    const file = fileInput.files[0];
    showToast('Uploading ' + file.name + '...');
    const url = await uploadFileAndGetUrl(file);
    if (url) {
      const fullUrl = url;
      if (targetId === 'slider_images') {
        // Add to slider images container
        addSliderImageRow(fullUrl);
        showToast('Image added to slider');
      } else {
        // Fill the target input
        const input = document.getElementById(targetId);
        if (input) {
          input.value = fullUrl;
          // Trigger preview update
          input.dispatchEvent(new Event('input', { bubbles: true }));
          showToast('Image set successfully');
        }
      }
    }
  });
  fileInput.click();
});

// ── Browse buttons: open media browser ──
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.browse-btn');
  if (!btn) return;
  const targetId = btn.dataset.target;
  if (!targetId) return;

  activeMediaTarget = targetId;
  openMediaBrowser();
});

// ── Upload slider button ──
document.getElementById('addSliderImageBtn')?.addEventListener('click', () => {
  addSliderImageRow('');
});

document.querySelector('.upload-slider-btn')?.addEventListener('click', () => {
  const fileInput = document.createElement('input');
  fileInput.type = 'file';
  fileInput.accept = 'image/*';
  fileInput.addEventListener('change', async () => {
    if (!fileInput.files.length) return;
    const file = fileInput.files[0];
    const url = await uploadFileAndGetUrl(file);
    if (url) {
      addSliderImageRow(url);
      showToast('Image added to slider');
    }
  });
  fileInput.click();
});

document.querySelector('.browse-slider-btn')?.addEventListener('click', () => {
  activeMediaTarget = 'slider_images';
  openMediaBrowser();
});

// ── Remove slider image ──
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.remove-slider-img');
  if (!btn) return;
  const row = btn.closest('.slider-img-row');
  if (row) row.remove();
});

// ── Add slider image row ──
function addSliderImageRow(url) {
  const container = document.getElementById('sliderImagesContainer');
  if (!container) return;
  const div = document.createElement('div');
  div.className = 'slider-img-row';
  div.style.cssText = 'display:flex;gap:.4rem;margin-bottom:.4rem;align-items:center';
  const imgUrl = url || '';
  const previewSrc = imgUrl.indexOf('http') === 0 ? imgUrl : '../' + imgUrl;
  div.innerHTML =
    '<img src="' + previewSrc + '" alt="" style="width:48px;height:36px;object-fit:cover;border-radius:4px" onerror="this.style.display=\'none\'">' +
    '<input type="url" class="slider-img-input" value="' + imgUrl + '" style="flex:1;padding:.4rem .6rem;background:rgba(255,255,255,.06);border:1.5px solid rgba(255,255,255,.1);border-radius:6px;color:var(--text);font-size:.78rem">' +
    '<button type="button" class="btn btn-sm btn-danger remove-slider-img"><i class="fas fa-times"></i></button>';
  container.appendChild(div);
}

// ── Media Browser ──
function openMediaBrowser() {
  const modal = document.getElementById('mediaBrowserModal');
  const grid = document.getElementById('mediaBrowserGrid');
  modal.classList.add('open');
  loadMediaBrowserGrid();
}

function closeMediaBrowser() {
  document.getElementById('mediaBrowserModal').classList.remove('open');
  activeMediaTarget = null;
}

async function loadMediaBrowserGrid() {
  const grid = document.getElementById('mediaBrowserGrid');
  grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:var(--text-muted);padding:2rem"><i class="fas fa-spinner fa-spin" style="font-size:1.5rem"></i><p>Loading...</p></div>';
  try {
    const res = await fetch('upload.php?list=1');
    const data = await res.json();
    grid.innerHTML = '';
    if (data.images && data.images.length) {
      data.images.forEach(url => {
        const div = document.createElement('div');
        div.className = 'uploaded-item';
        div.style.cursor = 'pointer';
        div.innerHTML = '<img src="../' + url + '" alt="" loading="lazy" style="height:100px"><div class="url-bar">' + url + '</div>';
        div.addEventListener('click', () => selectMediaImage(url));
        grid.appendChild(div);
      });
    } else {
      grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:var(--text-muted);padding:2rem"><i class="fas fa-images" style="font-size:2rem;display:block;margin-bottom:.5rem"></i><p>No images uploaded yet. Drag & drop or click to upload.</p></div>';
    }
  } catch(_) {
    grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;color:var(--danger);padding:2rem">Failed to load images.</div>';
  }
}

function selectMediaImage(url) {
  if (activeMediaTarget === 'slider_images') {
    addSliderImageRow(url);
    showToast('Image added to slider');
  } else if (activeMediaTarget) {
    const input = document.getElementById(activeMediaTarget);
    if (input) {
      input.value = url;
      input.dispatchEvent(new Event('input', { bubbles: true }));
      showToast('Image selected');
    }
  }
  closeMediaBrowser();
}

document.getElementById('mediaBrowserClose')?.addEventListener('click', closeMediaBrowser);
document.getElementById('mediaBrowserCancel')?.addEventListener('click', closeMediaBrowser);

// Upload in media browser
const mediaUploadZone = document.getElementById('mediaUploadZone');
const mediaFileInput = document.getElementById('mediaFileInput');

mediaUploadZone?.addEventListener('click', () => mediaFileInput.click());

mediaUploadZone?.addEventListener('dragover', (e) => {
  e.preventDefault();
  mediaUploadZone.classList.add('dragover');
});
mediaUploadZone?.addEventListener('dragleave', () => {
  mediaUploadZone.classList.remove('dragover');
});
mediaUploadZone?.addEventListener('drop', (e) => {
  e.preventDefault();
  mediaUploadZone.classList.remove('dragover');
  if (e.dataTransfer.files.length) handleMediaUpload(e.dataTransfer.files);
});

mediaFileInput?.addEventListener('change', () => {
  if (mediaFileInput.files.length) handleMediaUpload(mediaFileInput.files);
});

async function handleMediaUpload(files) {
  for (let i = 0; i < files.length; i++) {
    const file = files[i];
    const url = await uploadFileAndGetUrl(file);
    if (url) {
      const grid = document.getElementById('mediaBrowserGrid');
      const div = document.createElement('div');
      div.className = 'uploaded-item';
      div.style.cursor = 'pointer';
      div.innerHTML = '<img src="../' + url + '" alt="" loading="lazy" style="height:100px"><div class="url-bar">' + url + '</div>';
      div.addEventListener('click', () => selectMediaImage(url));
      grid.prepend(div);
    }
  }
  mediaFileInput.value = '';
}

// ── Image preview on URL input change ──
function setupImagePreview(inputId, previewId) {
  const input = document.getElementById(inputId);
  const preview = document.getElementById(previewId);
  if (!input || !preview) return;
  function updatePreview() {
    const val = input.value.trim();
    if (val) {
      const src = val.indexOf('http') === 0 ? val : '../' + val;
      preview.innerHTML = '<img src="' + src + '" alt="" style="max-height:60px;border-radius:4px" onerror="this.style.display=\'none\'">';
    } else {
      preview.innerHTML = '';
    }
  }
  input.addEventListener('input', updatePreview);
  input.addEventListener('change', updatePreview);
  // Initial preview
  updatePreview();
}

setupImagePreview('productImage', 'productImagePreview');
setupImagePreview('feedImage', 'feedImagePreview');
setupImagePreview('heroBgImage', 'heroBgPreview');

// ── Hero form submission (with proper slider_images handling) ──
document.getElementById('heroForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const fd = new FormData(e.target);

  // Collect slider images properly
  const sliderInputs = document.querySelectorAll('#sliderImagesContainer .slider-img-input');
  const sliderImages = [];
  sliderInputs.forEach(inp => {
    const val = inp.value.trim();
    if (val) sliderImages.push(val);
  });

  const data = {};
  fd.forEach((v, k) => { data[k] = v; });
  if (data.chips) data.chips = data.chips.split(',').map(s => s.trim()).filter(Boolean);

  // Override slider_images with properly collected array
  data.slider_images = sliderImages;

  const res = await apiPost('save-hero.php', data);
  if (res.success) {
    showToast(res.message || 'Hero saved successfully');
  } else {
    showToast(res.message || 'Failed to save hero', 'error');
  }
});
</script>
</body>
</html>
