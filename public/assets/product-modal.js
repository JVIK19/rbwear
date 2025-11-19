(function(){
  const modal = document.getElementById('product-modal');
  if(!modal) return;
  const wrap = modal.querySelector('.pmodal-content-wrap');
  const loader = modal.querySelector('.pmodal-loader');
  const toast = modal.querySelector('.pmodal-toast');
  const btnX = modal.querySelector('.pmodal-x');
  const backdrop = modal.querySelector('.pmodal-backdrop');

  function show(){ modal.hidden = false; document.body.style.overflow='hidden'; }
  function hide(){
    if (modal.hidden) return;
    modal.hidden = true;
    document.body.style.overflow='';
    wrap.innerHTML='';
  }
  function showLoader(){ loader.hidden=false; loader.innerHTML = modal.querySelector('#pmodal-loader-tpl')?.innerHTML || '<div class="spinner"></div>'; }
  function hideLoader(){ loader.hidden=true; loader.innerHTML=''; }
  function showToast(msg){ toast.textContent = msg; toast.hidden=false; setTimeout(()=>toast.hidden=true, 2500); }

  async function openProduct(id){
    try{
      show(); showLoader();
      const res = await fetch(`${baseUrl()}/produto/${id}/partial`, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
      const html = await res.text();
      wrap.innerHTML = html;
      initInside();
    }catch(e){ showToast('Falha ao carregar produto'); }
    finally{ hideLoader(); }
  }

  function baseUrl(){
    try{
      const href = document.querySelector('a.logo')?.getAttribute('href');
      if(href) return href.replace(/\/$/, '');
    }catch(e){}
    return (window.RB_BASE_URL) || '/RBWEAR_SITE';
  }

  function initInside(){
    const closeBtn = wrap.closest('#product-modal').querySelector('.pmodal-close');
    if (closeBtn) closeBtn.addEventListener('click', hide, { once: true });

    // Tabs
    const tabs = wrap.querySelectorAll('.pmodal-tab');
    const panels = wrap.querySelectorAll('.pmodal-panel');
    tabs.forEach(tab=>{
      tab.addEventListener('click', ()=>{
        tabs.forEach(t=>t.classList.remove('active'));
        panels.forEach(p=>p.classList.remove('active'));
        tab.classList.add('active');
        const name = tab.getAttribute('data-tab');
        const panel = wrap.querySelector(`.pmodal-panel[data-tabpanel="${name}"]`);
        if(panel) panel.classList.add('active');
      });
    });

    // Quantity
    const qtyInput = wrap.querySelector('.qty-input');
    const qtyBtns = wrap.querySelectorAll('.qty-btn');
    const hiddenQtd = wrap.querySelector('.pmodal-qtd-field');
    function sync(){ if(hiddenQtd && qtyInput) hiddenQtd.value = Math.max(1, parseInt(qtyInput.value||'1',10)); }
    if(qtyInput){ qtyInput.addEventListener('change', sync); }
    qtyBtns.forEach(b=> b.addEventListener('click', ()=>{
      const act = b.getAttribute('data-act');
      let v = parseInt(qtyInput.value||'1',10);
      if(Number.isNaN(v) || v<1) v=1;
      if(act==='inc') v++;
      if(act==='dec') v = Math.max(1, v-1);
      qtyInput.value = v; sync();
    }));

    // Info buttons (Detalhes, Medidas, FAQ)
    const chips = wrap.querySelectorAll('.btn-chip');
    const infos = wrap.querySelectorAll('.pinfo');
    chips.forEach(ch => {
      ch.addEventListener('click', () => {
        const target = ch.getAttribute('data-info-target');
        if(!target) return;
        infos.forEach(sec => {
          sec.hidden = sec.getAttribute('data-info') !== target ? true : !sec.hidden;
        });
        const opened = wrap.querySelector(`.pinfo[data-info="${target}"]:not([hidden])`);
        if(opened){ opened.scrollIntoView({ behavior:'smooth', block:'nearest' }); }
      });
    });
  }

  document.addEventListener('click', function(e){
    // não intercepta envios de formulário ou elementos marcados
    if (e.target.closest('form')) return;
    if (e.target.closest('[data-no-modal]')) return;

    const el = e.target.closest('[data-product-id][data-open-modal]');
    if(!el) return;
    const id = el.getAttribute('data-product-id');
    if(!id) return;
    const isLink = !!e.target.closest('a');
    if(isLink) e.preventDefault();
    openProduct(id);
  });

  // Delegate close from document to be resilient
  document.addEventListener('click', (e)=>{
    if (e.target.closest('.pmodal-x') || e.target.closest('.pmodal-close') || e.target.closest('.pmodal-backdrop')){
      hide();
    }
  });
  // Prevent clicks inside dialog from bubbling to backdrop/document
  const dialog = modal.querySelector('.pmodal-dialog');
  if (dialog){ dialog.addEventListener('click', (e)=> e.stopPropagation()); }
  document.addEventListener('keydown', (e)=>{ if(e.key==='Escape' && !modal.hidden) hide(); });
})();
