// Função para verificar se o elemento está visível na tela
function isElementInViewport(el) {
  const rect = el.getBoundingClientRect();
  return (
    rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
    rect.bottom >= 0
  );
}

// Função para ativar animações quando o elemento entra na tela
function handleScrollAnimations() {
  const elements = document.querySelectorAll('[data-aos]');
  
  elements.forEach(element => {
    if (isElementInViewport(element)) {
      element.classList.add('aos-animate');
    }
  });
}

// Adiciona a classe de animação inicial
function initAnimations() {
  // Adiciona classe de animação para elementos iniciais
  const animatedElements = document.querySelectorAll('.animate-on-load');
  animatedElements.forEach((el, index) => {
    el.style.animationDelay = `${index * 0.1}s`;
    el.classList.add('animate-fade-in');
  });

  // Configura o Intersection Observer para animações de scroll
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('aos-animate');
      }
    });
  }, {
    threshold: 0.1
  });

  // Observa todos os elementos com data-aos
  document.querySelectorAll('[data-aos]').forEach(el => {
    observer.observe(el);
  });

  // Adiciona efeito de ripple nos botões
  document.querySelectorAll('.btn-hover').forEach(button => {
    button.addEventListener('click', function(e) {
      const rect = this.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      const ripple = document.createElement('span');
      ripple.style.left = `${x}px`;
      ripple.style.top = `${y}px`;
      ripple.classList.add('ripple-effect');
      
      this.appendChild(ripple);
      
      // Remove o efeito após a animação
      setTimeout(() => {
        ripple.remove();
      }, 600);
    });
  });
}

// Inicializa as animações quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
  initAnimations();
  
  // Adiciona evento de scroll para animações
  window.addEventListener('scroll', handleScrollAnimations);
  
  // Dispara uma vez no carregamento
  handleScrollAnimations();
});
