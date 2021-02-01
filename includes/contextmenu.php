<!-- dit bovenaan bij je css -->
<style>
	 .menu {
  width: 120px;
  box-shadow: 0 4px 5px 3px rgba(0, 0, 0, 0.2);
  position: absolute;
  display: none;
  z-index: 999;
  background-color: #1e2a31;
}
.menu a {
    color: #9bbcd1;

}
  .menu-options {
    list-style: none;
    padding: 10px 0;
    z-index: 999;
    margin-bottom: 0;
}
    .menu-option {
      font-weight: 500;
      font-size: 14px;
      padding: 10px 40px 10px 20px;
      cursor: pointer;
      z-index: 999;
}
      .menu-option:hover {
        background: rgba(0, 0, 0, 0.2);
      }
</style>

<!-- dit direrct onder de </header> tag -->
<div class="menu">
    <ul class="menu-options">
        <li class="menu-option" onclick="window.history.back();">Back</li>
        <li class="menu-option" onclick="location.reload();">Reload</li>
        <li class="menu-option"><a href="attackhub">AttackHub</a></li>
        <li class="menu-option"><a href="profile">Profile</a></li><!-- onclick="gotoprofile(this);" -->
        <li class="menu-option"><a href="sign_out">Logout</a></li>
    </ul>
</div>
<!-- en dat onderaan -->
        <script type="text/javascript">
            function gotoprofile() {
                window.Location.href = 'profile';
            }
            const menu = document.querySelector(".menu");
let menuVisible = false;

const toggleMenu = command => {
  menu.style.display = command === "show" ? "block" : "none";
  menuVisible = !menuVisible;
};

const setPosition = ({ top, left }) => {
  menu.style.left = `${left}px`;
  menu.style.top = `${top}px`;
  toggleMenu("show");
};

window.addEventListener("click", e => {
  if(menuVisible)toggleMenu("hide");
});

window.addEventListener("contextmenu", e => {
  e.preventDefault();
  const origin = {
    left: e.pageX,
    top: e.pageY
  };
  setPosition(origin);
  return false;
});
    </script>