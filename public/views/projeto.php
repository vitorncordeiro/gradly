<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['usuario_tipo'] != 'aluno') {
    echo "Acesso negado";
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8"/>
  <title>Gradly — Projeto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --green:        #00a661;
      --green-hover:  #008f52;
      --green-light:  #e8f7f0;
      --green-mid:    #b8e8d2;
      --green-text:   #006b40;
      --bg:           #f9fafb;
      --surface:      #ffffff;
      --surface2:     #f4f5f7;
      --border:       #e8eaed;
      --border-mid:   #d1d5db;
      --text-1:       #1a1a2e;
      --text-2:       #5f6b7a;
      --text-3:       #9aa5b4;
      --sidebar-w:    210px;
      --topbar-h:     52px;
      --r-sm:4px; --r-md:6px; --r-lg:8px; --r-xl:12px;
    }
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Inter',-apple-system,sans-serif;font-size:13.5px;background:var(--bg);color:var(--text-1);min-height:100vh;}

    /* TOPBAR */
    .topbar{position:fixed;top:0;left:0;right:0;height:var(--topbar-h);background:var(--surface);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 1.25rem;z-index:200;gap:0;}
    .topbar-brand{display:flex;align-items:center;gap:8px;font-size:15px;font-weight:600;color:var(--text-1);text-decoration:none;padding-right:1.25rem;border-right:1px solid var(--border);min-width:var(--sidebar-w);}
    .topbar-brand svg{width:22px;height:22px;}
    .topbar-right{display:flex;align-items:center;gap:8px;margin-left:auto;}
    .icon-btn{width:30px;height:30px;border:none;background:transparent;border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-2);transition:background .12s;}
    .icon-btn:hover{background:var(--surface2);}
    .icon-btn svg{width:16px;height:16px;stroke:currentColor;fill:none;stroke-width:1.75;}

    /* SIDEBAR */
    .sidebar{position:fixed;top:var(--topbar-h);left:0;width:var(--sidebar-w);height:calc(100vh - var(--topbar-h));background:var(--surface);border-right:1px solid var(--border);overflow-y:auto;z-index:100;padding:.75rem 0 1.5rem;}
    .sec-label{padding:.9rem 1rem .3rem;font-size:10px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--text-3);display:flex;align-items:center;justify-content:space-between;}
    .sec-label-inner{display:flex;align-items:center;}
    .si{width:14px;height:14px;stroke:var(--text-2);fill:none;stroke-width:1.75;margin-right:5px;vertical-align:middle;}
    .chev{width:12px;height:12px;stroke:var(--text-3);fill:none;stroke-width:2;}
    .snav{list-style:none;padding-left:0;margin-top:6px;}
    .snav a{display:block;padding:.5rem 1rem .5rem 1.75rem;font-size:13px;color:var(--text-2);text-decoration:none;border-left:2px solid transparent;transition:all .12s;}
    .snav a:hover{background:var(--green-light);color:var(--green-text);}
    .snav a.active{background:var(--green-light);color:var(--green);font-weight:500;border-left-color:var(--green);}

    /* MAIN */
    .main{margin-left:var(--sidebar-w);margin-top:var(--topbar-h);min-height:calc(100vh - var(--topbar-h));display:flex;flex-direction:column;}
    .content{display:flex;flex:1;}
    .content-main{flex:1;padding:1.75rem;min-width:0;}
    .page-title{font-size:22px;font-weight:600;letter-spacing:-.02em;color:var(--text-1);margin-bottom:1.25rem;}

    /* PROJECT CARD */
    .proj-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-xl);overflow:hidden;}
    .proj-header{padding:.9rem 1.25rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--border);gap:10px;}
    .proj-card-title{font-size:15px;font-weight:600;color:var(--text-1);margin:0;}
    .proj-meta{padding:.9rem 1.25rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--border);gap:10px;}
    .proj-name{font-size:14px;font-weight:600;color:var(--text-1);margin-bottom:4px;}
    .proj-desc{font-size:12.5px;color:var(--text-3);}
    .sbadge{display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:99px;font-size:12px;font-weight:500;}
    .sbadge.pending{background:#fffbeb;color:#92400e;border:1px solid #fde68a;}
    .sbadge.pending::before{content:'';width:6px;height:6px;border-radius:50%;background:#f59e0b;flex-shrink:0;}
    .sbadge.approved{background:var(--green-light);color:var(--green-text);border:1px solid var(--green-mid);}
    .sbadge.approved::before{content:'';width:6px;height:6px;border-radius:50%;background:var(--green);flex-shrink:0;}
    .sbadge.error{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
    .sbadge.error::before{content:'';width:6px;height:6px;border-radius:50%;background:#ef4444;flex-shrink:0;}

    .info-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:0;}
    .info-item{padding:.85rem 1.25rem;border-bottom:1px solid var(--border);border-right:1px solid var(--border);}
    .info-item:nth-child(even){border-right:none;}
    .info-item:nth-last-child(-n+2){border-bottom:none;}
    .info-label{font-size:11px;font-weight:600;letter-spacing:.05em;text-transform:uppercase;color:var(--text-2);margin-bottom:6px;}
    .info-value{font-size:13.5px;color:var(--text-1);}

    .proj-docs{padding:1rem 1.25rem;border-top:1px solid var(--border);}
    .proj-docs-title{font-size:13px;font-weight:600;color:var(--text-1);margin-bottom:.6rem;}
    .proj-docs-empty{font-size:12.5px;color:var(--text-3);}
    .proj-doc{padding:.6rem .75rem;border:1px solid var(--border);border-radius:var(--r-lg);background:var(--surface2);margin-bottom:.6rem;}
    .proj-doc:last-child{margin-bottom:0;}
    .proj-doc-head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:.4rem;}
    .proj-doc-title{font-size:12.8px;font-weight:600;color:var(--text-1);}
    .proj-doc-count{font-size:11.5px;color:var(--text-2);}
    .proj-doc-list{display:flex;flex-direction:column;gap:.25rem;}
    .proj-doc-item{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:10px;font-size:12px;color:var(--text-2);}
    .proj-doc-actions{display:flex;gap:6px;}
    .doc-btn{width:26px;height:26px;border:1px solid var(--border-mid);border-radius:var(--r-md);background:white;display:inline-flex;align-items:center;justify-content:center;color:var(--text-2);cursor:pointer;transition:all .12s;}
    .doc-btn:hover{border-color:var(--green);color:var(--green);}
    .doc-btn svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:1.75;}

    .proj-doc-comments{width:100%;padding-top:.4rem;border-top:1px dashed var(--border);margin-top:.35rem;display:flex;flex-direction:column;gap:.35rem;}
    .proj-comment{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-md);padding:.45rem .6rem;}
    .proj-comment-meta{font-size:11px;color:var(--text-3);display:block;margin-bottom:.2rem;}
    .proj-comment-text{font-size:12.5px;color:var(--text-1);margin:0;}
    .proj-comment-empty{font-size:11.5px;color:var(--text-3);}

    .pdf-modal{position:fixed;inset:0;background:rgba(15,23,42,.55);display:none;align-items:center;justify-content:center;z-index:300;}
    .pdf-modal.active{display:flex;}
    .pdf-modal-card{width:min(900px,92vw);height:min(80vh,720px);background:var(--surface);border-radius:var(--r-xl);border:1px solid var(--border);overflow:hidden;display:flex;flex-direction:column;}
    .pdf-modal-head{display:flex;align-items:center;justify-content:space-between;padding:.6rem 1rem;border-bottom:1px solid var(--border);}
    .pdf-modal-title{font-size:13px;font-weight:600;color:var(--text-1);}
    .pdf-modal-body{flex:1;}
    .pdf-modal-body iframe{width:100%;height:100%;border:none;}

    .empty-state{border:1px dashed var(--border-mid);border-radius:var(--r-xl);padding:1.5rem;text-align:center;color:var(--text-2);background:var(--surface);}

    /* BUTTONS */
    .btn-primary{background:var(--green);color:white;border:1px solid var(--green);padding:6px 14px;border-radius:var(--r-md);font-size:13px;font-weight:500;font-family:inherit;cursor:pointer;transition:background .12s;display:inline-flex;align-items:center;gap:5px;white-space:nowrap;}
    .btn-primary:hover{background:var(--green-hover);border-color:var(--green-hover);}
    .btn-outline{background:white;color:var(--text-1);border:1px solid var(--border-mid);padding:6px 14px;border-radius:var(--r-md);font-size:13px;font-weight:500;font-family:inherit;cursor:pointer;transition:all .12s;display:inline-flex;align-items:center;gap:5px;white-space:nowrap;text-decoration:none;}
    .btn-outline:hover{border-color:var(--green);color:var(--green);}

    /* RIGHT PANEL */
    .right-panel{width:252px;flex-shrink:0;border-left:1px solid var(--border);background:var(--surface);padding:1.25rem;display:flex;flex-direction:column;gap:.75rem;}
    .panel-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:.25rem;}
    .panel-title{font-size:14px;font-weight:600;color:var(--text-1);}
    .panel-tabs{display:flex;border-bottom:1px solid var(--border);margin-bottom:.75rem;}
    .ptab{padding:.4rem .7rem;font-size:12.5px;font-weight:500;color:var(--text-2);border-bottom:2px solid transparent;margin-bottom:-1px;cursor:pointer;display:flex;align-items:center;gap:4px;}
    .ptab.active{color:var(--green);border-bottom-color:var(--green);}
    .badge-pill{background:var(--green);color:white;font-size:10px;font-weight:600;padding:1px 5px;border-radius:99px;}
    .tip-item{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-lg);padding:.65rem .85rem;display:flex;align-items:center;justify-content:space-between;cursor:pointer;}
    .tip-item + .tip-item{margin-top:6px;}
    .tip-lbl{font-size:12.5px;font-weight:500;color:var(--text-1);display:flex;align-items:center;gap:6px;}
    .tip-lbl svg{width:13px;height:13px;stroke:var(--text-2);fill:none;stroke-width:2;}
    .tip-chev{width:12px;height:12px;stroke:var(--text-3);fill:none;stroke-width:2;}

    /* STATUS BAR */
    .status-bar{border-top:1px solid var(--border);padding:.5rem 1.75rem;font-size:12px;color:var(--text-3);background:var(--surface);display:flex;align-items:center;gap:6px;}
    .status-dot{width:7px;height:7px;border-radius:50%;background:var(--green);}
    .status-bar a{color:var(--green);text-decoration:none;font-weight:500;}

    /* ANIMATIONS */
    @keyframes fadeUp{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);}}
    .fi{animation:fadeUp .35s ease both;}
    .fi-1{animation-delay:.04s;}.fi-2{animation-delay:.1s;}.fi-3{animation-delay:.17s;}.fi-4{animation-delay:.24s;}.fi-5{animation-delay:.3s;}
  </style>
</head>
<body>
  <!-- TOPBAR -->
  <header class="topbar">
    <a class="topbar-brand" href="#">Gradly</a>

    <div class="topbar-right">
      <button class="icon-btn"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></button>
      <button class="icon-btn"><svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></button>
      <button class="icon-btn"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 4.93l1.41 1.41M12 2v2M12 20v2M2 12h2M20 12h2M19.07 19.07l-1.41-1.41M4.93 19.07l1.41-1.41"/></svg></button>
      <span style="text-transform:capitalize">
        <?php
        echo $_SESSION['usuario_nome'];
        ?>
      </span>
      <button class="icon-btn" id="logout">
        <svg viewBox="0 0 24 24" width="16" height="16" style="stroke:gray;fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
      </button>
    </div>
  </header>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sec-label">
      <div class="sec-label-inner">
        <svg class="si" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Início
      </div>
      <svg class="chev" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
    </div>

    <ul class="snav">
      <li><a href="dashboard_aluno.php">Dashboard</a></li>
    </ul>

    <div class="sec-label" style="margin-top:.6rem;">
      <div class="sec-label-inner">
        <svg class="si" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/></svg>
        Projeto
      </div>
      <svg class="chev" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
    </div>

    <ul class="snav">
      <li><a href="projeto.php" class="active">Projeto</a></li>
      <li><a href="cadastro_projeto.php">Criar Projeto</a></li>
      <li><a href="#">Tarefas</a></li>
      <li><a href="#">Documento</a></li>
    </ul>
  </aside>

  <!-- MAIN -->
  <main class="main">
    <div class="content">
      <div class="content-main">
        <h1 class="page-title fi fi-1">Projeto</h1>

        <div id="projectContainer" class="fi fi-2"></div>
      </div>

      <aside class="right-panel fi fi-5">
        <div class="panel-head">
          <span class="panel-title">Painel Rápido</span>
          <button class="icon-btn" style="width:28px;height:28px;"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 4.93l1.41 1.41M12 2v2M12 20v2M2 12h2M20 12h2M19.07 19.07l-1.41-1.41M4.93 19.07l1.41-1.41"/></svg></button>
        </div>
        <div class="panel-tabs">
          <span class="ptab active">Projeto <span class="badge-pill">2</span></span>
          <span class="ptab">Dicas</span>
        </div>
        <div class="tip-item">
          <span class="tip-lbl"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 4.93l1.41 1.41M12 2v2M12 20v2M2 12h2M20 12h2M19.07 19.07l-1.41-1.41M4.93 19.07l1.41-1.41"/></svg> Revisar objetivos</span>
          <svg class="tip-chev" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
        </div>
        <div class="tip-item">
          <span class="tip-lbl"><svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg> Atualizar temas</span>
          <svg class="tip-chev" viewBox="0 0 24 24"><polyline points="18 15 12 9 6 15"/></svg>
        </div>
      </aside>
    </div>

    <div class="status-bar">
      <div class="status-dot"></div>
      Status do sistema: <a href="#">Tudo operacional</a>
    </div>
  </main>

  <script src="../assets/service/projeto/buscar_projeto.js"></script>
  <script src="../assets/service/controle/logout.js"></script>
</body>
</html>
