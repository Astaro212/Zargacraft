<!DOCTYPE html>
<html lang="">

<head>
    <title>ZargaCraft</title>
    <meta description="">
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="mainwrapper">
        <header>
            <div class="nav">
                <label for="checkbox">
                    <i class="fa fa-bars menu-icon"></i>
                </label>
                <input type="checkbox" id="checkbox">
                <nav class="menu">
                    <ul>
                        <li><a href="#">ГЛАВНАЯ</a></li>
                        <li><img src="assets/img/logo.png" alt="logo"></li>
                        <li><a href="/donate">МАГАЗИН</a></li>
                        <li><a href="/rules">ПРАВИЛА</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main>
            <div class="slider">
                <div class="slider_inner">
                    <div class="slider_item">
                        <img src="assets/img/pic_1.png" alt="pic1">
                    </div>
                    <div class="slider_item">
                        <img src="assets/img/pic_2.png" alt="pic2">
                    </div>
                    <div class="slider_item">
                        <img src="assets/img/pic_3.png" alt="pic3">
                    </div>
                    <div class="slider_item"><img src="assets/img/pic_4.png" alt="pic4"></div>
                    <div class="slider_item"><img src="assets/img/pic_5.png" alt="pic5"></div>
                    <div class="slider_item"><img src="assets/img/pic_6.png" alt="pic6"></div>
                    <div class="slider_item"><img src="assets/img/pic_7.png" alt="pic7"></div>
                </div>
                <script>
                    let current = 0;
                    const sliderItems = document.querySelectorAll('.slider_item');
                    const sliderInner = document.querySelector('.slider_inner');
                    const sliderItemWidth = sliderItems[0].offsetWidth;

                    function slide(index) {
                        if (index < 0) {
                            index = sliderItems.length - 1;
                        } else if (index >= sliderItems.length) {
                            index = 0;
                        }
                        current = index;
                        sliderInner.style.transform = `translateX(-${current * sliderItemWidth}px)`;
                    }

                    function nextSlide() {
                        if (current < sliderItems.length - 1) {
                            slide(current + 1);
                        } else {
                            slide(0);
                        }
                    }

                    setInterval(nextSlide, 6000);
                </script>
            </div>
            <div class="join">
                <p>Присоединяйтесь к нам! Наш ip - <span>zargacraft.com</span>!!</p>
            </div>
            <div class="priv">
                <h3>Описание привилегий</h3>
                <table class="priv_desc">
                    <th></th>
                    <tbody>
                        <tr>
                            <td><strong>WARRIOR</strong></td>
                            <td><strong>Команды: </strong><br>
                                /kit warrior, /workbench, /tpahere, /anvil, /fireball<br>
                                <strong>Особые возможности:</strong><br>
                                +3 блока привата, +2 точки дома, +5 слотов аукциона;
                            </td>
                        </tr>
                        <tr>
                            <td><strong>GUARDIAN</strong></td>
                            <td><strong>Команды: </strong><br>
                                /kit guardian, /hat, /feed, /pweather, /stonecutter<br>
                                <strong>Особые возможности:</strong><br>
                                +5 блоков привата, +3 точки дома, +7 слотов аукциона <br> + возможность писать жирным в чат;
                            </td>
                        </tr>
                        <tr>
                            <td><strong>PALADIN</strong></td>
                            <td><strong>Команды: </strong><br>
                                /kit paladin, /ec, /ignore, /smithingtable, /loom <br>
                                <strong>Особые возможности:</strong><br>
                                +6 блоков привата, +4 точки дома, +9 слотов аукциона <br> + возможность писать цветом в чат;
                            </td>
                        </tr>
                        <tr>
                            <td><strong>MASTER</strong></td>
                            <td><strong>Команды: </strong><br>
                                /kit master, /repair, /ptime, /grindstone, /cartographytable <br>
                                <strong>Особые возможности:</strong><br>
                                +7 блоков привата, +5 точки дома, +15 слотов аукциона <br> + возможности пред.привилегий;
                            </td>
                        </tr>
                        <tr>
                            <td><strong>LEADER</strong></td>
                            <td><strong>Команды: </strong><br>
                                /kit leader, /setwarp, /invsee, /heal, /near, /back <br>
                                <strong>Особые возможности:</strong><br>
                                +8 блоков привата, +6 точки дома, +18 слотов аукциона <br> + возможности пред.привилегий;
                            </td>
                        </tr>
                        <tr>
                            <td><strong>PRINCE</strong></td>
                            <td><strong>Команды: </strong><br>
                                /kit prince, /prefix, /bc, /nick <br>
                                <strong>Особые возможности:</strong><br>
                                +10 блоков привата, +8 точки дома, +26 слотов аукциона <br> + возможности пред.привилегий;
                            </td>
                        </tr>
                        <tr>
                            <td><strong>KING</strong></td>
                            <td><strong>Команды: </strong><br>
                                /kit king, /prefix, /realname, /near, /sun, /rain, /day,<br> /night, /repair all, /seen, /ext <br>
                                <strong>Особые возможности:</strong><br>
                                +25 блоков привата, +20 точки дома, +60 слотов аукциона <br> + возможности пред.привилегий;
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
        <footer>
            <div class="policies">
                <a href="#">Публичная оферта</a>
                <a href="#">Политика конфиденциальности</a>
                <a href="#">Email: zargacraft@gmail.com</a>
            </div>
            <p class="after">©Zargacraft,2025.<br>
                Zargacraft не связан с MojangAB.</p>
        </footer>
    </div>
</body>

</html>