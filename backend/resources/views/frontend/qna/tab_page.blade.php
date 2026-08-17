<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>              

</head>
<style>
    /* 1. Design tokens */
:root {
  /* Brand */
  --primary: #1E88E5;
  --success: #2E7D32;
  --warning: #F57C00;
  --danger:  #C62828;
  --bg:      #F9FAFB;

  /* Neutrals */
  --gray-900: #111827;
  --gray-700: #374151;
  --gray-500: #6b7280;
  --gray-200: #e5e7eb;
  --gray-100: #f3f4f6;

  /* Derived */
  --primary-soft: #E3F2FD;
  --success-soft: #ECFDF5;
}

/* 2. Base and typography */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  font-size: 14px;              /* Body */
  background: var(--bg);
  color: var(--gray-900);
}

a {
  color: inherit;
  text-decoration: none;
}

h1 {
  font-size: 28px;              /* H1 */
}

h2 {
  font-size: 22px;              /* H2 */
}

.meta {
  font-size: 12px;              /* Meta */
  color: var(--gray-500);
}

/* 3. Page shell + header */
.page-shell {
  max-width: 100%;
  margin: 0 auto;
  padding: 16px;
}

.global-header {
  display: flex;
  align-items: center;
  justify-content: center;
  padding-bottom: 12px;
  border-bottom: 1px solid var(--gray-200);
  margin-bottom: 12px;
  gap: 12px;
}

.brand {
  font-weight: 700;
  font-size: 20px;
  width: 100%;
}

/* Button/Primary */
.btn-primary {
  padding: 8px 14px;
  background: #fc5e02;;
  color:white;
  border-radius: 999px;
  border: none;
  cursor: pointer;
  font-size: 14px;
}

/* 4. Search + tabs */
.search-row {
  display: flex;
  gap: 12px;
  margin: 12px 0;
  flex-wrap: wrap;
}

.search-input {
  flex: 1;
  min-width: 220px;
  padding: 8px 12px;
  border-radius: 999px;
  border: 1px solid var(--gray-200);
  font-size: 14px;
}

.tabs {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.tab {
  padding: 6px 12px;
  border-radius: 999px;
  border: 1px solid transparent;
  font-size: 13px;
  cursor: pointer;
  background: transparent;
  color: var(--gray-700);
}

.tab.active {
  background: var(--primary-soft);
  border-color: var(--primary);
  color: var(--primary);
}
.tab:hover{
    color:#F57C00;
}
/* 5. Layout: sidebar + feed */
.layout {
  display: grid;
  grid-template-columns: 260px minmax(0, 1fr);
  gap: 16px;
  margin-top: 8px;
  width: 100%!important;
  padding-left: 0% !important
}
.right{
    display: grid;
  grid-template-columns: 260px minmax(0, 1fr);
  gap: 16px;
  margin-top: 8px;
  width: 100%!important;
  padding-left: 0% !important;
  background-color: #ffffff;
  height:300px;
  

}

@media (max-width: 768px) {
  .layout {
    grid-template-columns: minmax(0, 1fr);
  }
}

/* Sidebar filter */
.sidebar {
  background: #fff;
  border-radius: 12px;
  padding: 12px;
  border: 1px solid var(--gray-200);
  font-size: 14px;
}

.sidebar h3 {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 8px;
}

.filter-group {
  margin-bottom: 12px;
}

.filter-label {
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.03em;
  color: var(--gray-500);
  margin-bottom: 4px;
}

.filter-select {
  width: 100%;
  padding: 6px 8px;
  border-radius: 8px;
  border: 1px solid var(--gray-200);
  font-size: 13px;
  color: var(--gray-700);
}

/* 6. Feed + PostCard/QnA */
.feed {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

/* PostCard/QnA base */
.card {
  background: #fff;
  border-radius: 12px;
  border: 1px solid var(--gray-200);
  padding: 12px;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 4px;
  gap: 8px;
}

.card-title {
  font-size: 15px;
  font-weight: 600;
}

.card-body-text {
  font-size: 13px;
  color: var(--gray-700);
  margin-top: 4px;
}

.card-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 6px;
  font-size: 12px;
  color: var(--gray-500);
}

.stats {
  display: flex;
  gap: 10px;
}

.stat {
  display: inline-flex;
  align-items: center;
  gap: 4px;
}

.stat-number {
  font-weight: 600;
  color: var(--gray-900);
}

/* 7. Badges */
.badge-row {
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
}

.badge {
  font-size: 11px;
  padding: 3px 8px;
  border-radius: 999px;
  border: 1px solid var(--gray-200);
  color: var(--gray-700);
}

/* Badge/Category */
.badge-category {
  border-color: var(--primary);
  color: var(--primary);
  background: var(--primary-soft);
}

/* Badge/Location */
.badge-location {
  border-color: var(--gray-200);
  color: var(--gray-700);
  background: #fff;
}

/* Q&A type badge */
.badge-qa {
  border-color: var(--primary);
  color: var(--primary);
  background: var(--primary-soft);
}

/* Solved / success */
.badge-solved {
  border-color: var(--success);
  color: var(--success);
  background: var(--success-soft);
}

/* Marketplace example */
.badge-market {
  border-color: var(--warning);
  color: var(--warning);
  background: #FFF7ED;
}

/* 8. Best answer highlight */
.card-best-answer {
  border-color: var(--success);
  background: var(--success-soft);
}

/* 9. Ask Question modal */
.modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.25);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 99999;
}

.modal-backdrop.show {
  display: flex;
}

.modal-surface {
  background: #fff;
  border-radius: 16px;
  width: 100%;
  max-width: 520px;
  padding: 16px 18px;
  box-shadow: 0 18px 40px rgba(15, 23, 42, 0.26);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.modal-close {
  border: none;
  background: none;
  font-size: 18px;
  cursor: pointer;
}

.modal-helper {
  font-size: 12px;
  color: var(--gray-500);
  margin-bottom: 10px;
}

.form-field {
  margin-bottom: 8px;
}

.form-label {
  font-size: 13px;
  display: block;
  margin-bottom: 4px;
}

.form-input,
.form-textarea,
.form-select {
  width: 100%;
  padding: 8px 10px;
  border-radius: 10px;
  border: 1px solid var(--gray-200);
  font-size: 13px;
}

.form-textarea {
  resize: vertical;
}

.form-hint {
  font-size: 11px;
  color: var(--gray-500);
  margin-top: 2px;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
}

.btn-secondary {
  border: none;
  background: #f3f4f6;
  border-radius: 999px;
  padding: 8px 14px;
  font-size: 13px;
  cursor: pointer;
}

/* 10. Toast/Points */
.toast-points {
  position: fixed;
  bottom: 88px;
  left: 50%;
  transform: translateX(-50%);
  background: #111827;
  color: #f9fafb;
  padding: 10px 14px;
  border-radius: 999px;
  font-size: 12px;
  display: none;
  align-items: center;
  gap: 8px;
  z-index: 50;
}

.toast-bar {
  width: 80px;
  height: 4px;
  background: #374151;
  border-radius: 999px;
  overflow: hidden;
}

.toast-bar-fill {
  width: 60%;
  height: 100%;
  background: var(--success);
}

/* 11. Solved screen */
.solved-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.85);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 60;
}

.solved-backdrop.show {
  display: flex;
}

.solved-surface {
  background: #fff;
  border-radius: 18px;
  padding: 20px 18px;
  width: 100%;
  max-width: 420px;
  text-align: center;
}

.solved-actions {
  display: flex;
  gap: 8px;
  justify-content: center;
}

/* 12. Thread detail + sticky reply */
.thread-header {
  margin-bottom: 6px;
}

.thread-question-title {
  font-size: 18px;
  margin-bottom: 4px;
}

.thread-question-body {
  font-size: 13px;
  color: var(--gray-700);
}

.reply-section-title {
  font-size: 14px;
  margin-bottom: 6px;
}

.reply-block {
  padding: 6px 0;
  border-bottom: 1px solid var(--gray-100);
}

.reply-nested {
  margin-left: 14px;
  border-left: 2px solid var(--gray-100);
  padding-left: 8px;
  margin-top: 4px;
}

/* Sticky reply input */
.reply-input-bar {
  position: fixed;
  left: 0;
  right: 0;
  bottom: 0;
  background: #ffffff;
  border-top: 1px solid var(--gray-200);
  padding: 8px 16px;
  display: flex;
  gap: 8px;
  align-items: flex-end;
  z-index: 30;
}

.reply-input {
  flex: 1;
  min-height: 36px;
  max-height: 80px;
  resize: vertical;
  border-radius: 999px;
  padding: 8px 12px;
  border: 1px solid var(--gray-200);
  font-size: 13px;
}

.reply-button {
  border: none;
  border-radius: 999px;
  padding: 8px 14px;
  font-size: 13px;
  background: var(--primary);
  color: #fff;
  cursor: pointer;
}
/* section{
  background-color:white;

} */
  .container{
    width:100%;
  }
.section{
  width:100%;
}
    
    
</style>
    <body>

    <div class="container">
    <form
    action="{{ route('search') }}"  {{-- apna route naam --}}
    method="GET"
    class="d-flex align-items-center gap-2 px-3 py-1"
   
>
<div class="section">

{{-- left icon (Bootstrap icons / Heroicons / SVG) --}}
    <span class="text-muted">
        <i class="bi bi-search"></i> {{-- ya apna SVG --}}
    </span>

    <input
        type="text"
        name="q"
        class="border-1 flex-grow-1 p-3"
        placeholder="Search ...... "  
      
         style="background:#fff;border-radius:999px;max-width:600px;width:100%;"
       >

    {{-- optional filter like "r/subreddit" pill --}}
   <!-- <span class="badge bg-gray border border-secondary">r/ahmedabad</span>  -->


    <div class="tabs p-3 ">
        <button class="tab active">All</button>
        <button class="tab">Services</button>
        <button class="tab">Food</button>
        <button class="tab">Events</button>
        <button class="tab">Nearby</button>
    </div>
</div>
</form>




    
</div>  
 
</body>
</html>


    
