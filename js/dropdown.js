function myDropdown(){
    let dropdown = document.querySelector('#dropdownButton #dropdown');
    dropdown.classList.toggle('hidden');
}
window.onclick = function(event) {
    const dropdown = document.getElementById("dropdown");
    const dropdownButton = document.getElementById("dropdownButton");
    if (!dropdownButton.contains(event.target)) {
        dropdown.classList.add("hidden");
    }
}