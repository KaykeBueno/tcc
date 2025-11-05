function exibeMenu() {
    const sidebar = document.getElementById("sidebar");

    if (sidebar.classList.contains("closed")) {
        sidebar.classList.remove("closed");
    }
    else {
        sidebar.classList.add("closed");
    }
}