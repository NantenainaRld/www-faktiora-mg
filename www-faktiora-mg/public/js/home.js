document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    //template real content
    const templateRealContent = document.getElementById("template-home");
    // container
    const container = document.getElementById("container");
    // load template real
    container.append(templateRealContent.content.cloneNode(true));
  }, 1050);
});
