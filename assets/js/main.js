// ============================================================
// 🫧 clay interactions — vanilla JS, no dependencies
// ============================================================

// ---------- nav shrinks after scrolling ----------
const navPill = document.getElementById('nav-pill')

function onScroll() {
  if (navPill) navPill.classList.toggle('scrolled', window.scrollY > 40)
}
window.addEventListener('scroll', onScroll, { passive: true })
onScroll()

// ---------- scroll reveal (staggered via --rd custom property) ----------
const observer = new IntersectionObserver(
  (entries) => {
    for (const entry of entries) {
      if (entry.isIntersecting) {
        entry.target.classList.add('in')
        observer.unobserve(entry.target)
      }
    }
  },
  { threshold: 0.12 },
)
document.querySelectorAll('.reveal').forEach((el) => observer.observe(el))

// ---------- project cards tilt toward the cursor ----------
document.querySelectorAll('.project').forEach((card) => {
  card.addEventListener('mousemove', (e) => {
    const rect = card.getBoundingClientRect()
    const px = (e.clientX - rect.left) / rect.width - 0.5
    const py = (e.clientY - rect.top) / rect.height - 0.5
    card.style.transform = `perspective(900px) rotateX(${(-py * 7).toFixed(2)}deg) rotateY(${(px * 9).toFixed(2)}deg)`
  })
  card.addEventListener('mouseleave', () => {
    card.style.transform = ''
  })
})

// ---------- squishy fake form submit ----------
const form = document.querySelector('#contact form')
const submitBtn = form ? form.querySelector('.submit') : null

if (form && submitBtn) {
  form.addEventListener('submit', (e) => {
    e.preventDefault()
    if (submitBtn.classList.contains('done')) return

    const target = window.location.href.split('#')[0]
    fetch(target, { method: 'POST', body: new FormData(form) })
      .then(() => {
        submitBtn.classList.add('done')
        form.reset()
        setTimeout(() => submitBtn.classList.remove('done'), 2600)
      })
      .catch(() => {
        // Fallback: submit the old-fashioned way if fetch fails.
        form.submit()
      })
  })
}
