// Active nav link based on current page
(function(){
  const path = (location.pathname.split("/").pop() || "index.html").toLowerCase();
  document.querySelectorAll(".nav a[data-page]").forEach(a=>{
    if(a.getAttribute("data-page") === path) a.classList.add("active");
  });
})();

// Tabs (used on services page + home page)
(function(){
  document.querySelectorAll("[data-tabs]").forEach(tabsEl=>{
    const btns = tabsEl.querySelectorAll("[data-tab-btn]");
    const panels = tabsEl.querySelectorAll("[data-tab-panel]");

    function activate(key){
      btns.forEach(b => b.classList.toggle("active", b.getAttribute("data-tab-btn") === key));
      panels.forEach(p => p.classList.toggle("active", p.getAttribute("data-tab-panel") === key));
    }

    btns.forEach(b=>{
      b.addEventListener("click", ()=> activate(b.getAttribute("data-tab-btn")));
    });

    // default
    const first = btns[0]?.getAttribute("data-tab-btn");
    if(first) activate(first);
  });
})();

// Contact form -> mailto (simple & free hosting friendly)
(function(){
  const form = document.getElementById("mailform");
  if(!form) return;

  form.addEventListener("submit", (e)=>{
    e.preventDefault();
    const name = document.getElementById("name")?.value?.trim() || "";
    const org  = document.getElementById("org")?.value?.trim() || "NA";
    const email= document.getElementById("email")?.value?.trim() || "";
    const phone= document.getElementById("phone")?.value?.trim() || "NA";
    const topic= document.getElementById("topic")?.value?.trim() || "Consulting inquiry";
    const msg  = document.getElementById("msg")?.value?.trim() || "";

    const subject = encodeURIComponent("Consulting inquiry: " + topic);
    const body = encodeURIComponent(
      "Name: " + name + "\n" +
      "Organization: " + org + "\n" +
      "Email: " + email + "\n" +
      "Phone: " + phone + "\n\n" +
      "Topic: " + topic + "\n\n" +
      "Message:\n" + msg + "\n\n" +
      "Sent via www.asanienterprises.com"
    );

    window.location.href = "mailto:info@asanienterprises.com?subject=" + subject + "&body=" + body;
  });
})();

// Footer year
(function(){
  document.querySelectorAll("[data-year]").forEach(el => el.textContent = new Date().getFullYear());
})();
