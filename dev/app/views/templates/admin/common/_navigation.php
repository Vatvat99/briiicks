<a class="logo" href="/admin/home/index" title="Revenir à l'accueil">
    <img src="/assets/img/admin/gear-logo-02.png">
</a>
<div id="navigation-button-container" class="navigation-button-container">
    <button id="navigation-button" class="navigation-button inactive" type="button">menu</button>
</div>

<ul id="mobile-navigation" class="mobile-navigation">
    <li class="<?php echo (isset($_GET['page']) && $_GET['page'] == 'admin/home') ? 'active' : 'inactive'; ?>">
        <a href="/admin/home/index">
            <h3>
                <span class="av-icon-24-home"></span>
                Accueil
            </h3>
        </a>
    </li>
    <li class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/minifigures/listing' ||  $_GET['page'] == 'admin/minifigures/add' ||  $_GET['page'] == 'admin/minifigures/edit' || $_GET['page'] == 'admin/sets/listing' || $_GET['page'] == 'admin/sets/add' || $_GET['page'] == 'admin/sets/edit' || $_GET['page'] == 'admin/ranges/listing' || $_GET['page'] == 'admin/ranges/add' || $_GET['page'] == 'admin/ranges/edit' || $_GET['page'] == 'admin/series/listing' || $_GET['page'] == 'admin/series/add' || $_GET['page'] == 'admin/series/edit' || $_GET['page'] == 'admin/offers/listing' || $_GET['page'] == 'admin/offers/add' || $_GET['page'] == 'admin/offers/edit' || $_GET['page'] == 'admin/members/listing'|| $_GET['page'] == 'admin/members/add' || $_GET['page'] == 'admin/members/edit')) ? 'active' : 'inactive'; ?>">
        <a class="category" href="#">
            <h3>
                <span class="av-icon-24-lego"></span>
                Briiicks
            </h3>
        </a>
        <ul>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/minifigures/listing' || $_GET['page'] == 'admin/minifigures/add' || $_GET['page'] == 'admin/minifigures/edit')) ? 'active' : 'inactive'; ?>" href="/admin/minifigures/listing">
					<span class="icon-container">
						<span class="av-icon-24-round"></span>
						<span class="av-icon-24-arrow-right icon-layer"></span>
					</span>
                    Figurines
                </a>
            </li>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/sets/listing' || $_GET['page'] == 'admin/sets/add' || $_GET['page'] == 'admin/sets/edit')) ? 'active' : 'inactive'; ?>" href="/admin/sets/listing">
					<span class="icon-container">
						<span class="av-icon-24-round"></span>
						<span class="av-icon-24-arrow-right icon-layer"></span>
					</span>
                    Sets
                </a>
            </li>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/ranges/listing' || $_GET['page'] == 'admin/ranges/add' || $_GET['page'] == 'admin/ranges/edit')) ? 'active' : 'inactive'; ?>" href="/admin/ranges/listing">
					<span class="icon-container">
						<span class="av-icon-24-round"></span>
						<span class="av-icon-24-arrow-right icon-layer"></span>
					</span>
                    Gammes
                </a>
            </li>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/series/listing' || $_GET['page'] == 'admin/series/add' || $_GET['page'] == 'admin/series/edit')) ? 'active' : 'inactive'; ?>" href="/admin/series/listing">
					<span class="icon-container">
						<span class="av-icon-24-round"></span>
						<span class="av-icon-24-arrow-right icon-layer"></span>
					</span>
                    Séries
                </a>
            </li>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/offers/listing' || $_GET['page'] == 'admin/offers/add' || $_GET['page'] == 'admin/offers/edit')) ? 'active' : 'inactive'; ?>" href="/admin/offers/listing">
					<span class="icon-container">
						<span class="av-icon-24-round"></span>
						<span class="av-icon-24-arrow-right icon-layer"></span>
					</span>
                    Annonces
                </a>
            </li>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/members/listing' || $_GET['page'] == 'admin/members/add' || $_GET['page'] == 'admin/members/edit')) ? 'active' : 'inactive'; ?>" href="/admin/members/listing">
					<span class="icon-container">
						<span class="av-icon-24-round"></span>
						<span class="av-icon-24-arrow-right icon-layer"></span>
					</span>
                    Membres
                </a>
            </li>
        </ul>
    </li>
    <li class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/users/listing' || $_GET['page'] == 'admin/users/add' || $_GET['page'] == 'admin/users/edit')) ? 'active' : 'inactive'; ?>">
        <a class="category" href="#">
            <h3>
                <span class="av-icon-24-equalizer"></span>
                Paramètres
            </h3>
        </a>
        <ul>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/users/listing' || $_GET['page'] == 'admin/users/add' || $_GET['page'] == 'admin/users/edit')) ? 'active' : 'inactive'; ?>" href="/admin/users/listing">
					<span class="icon-container">
						<span class="av-icon-24-round"></span>
						<span class="av-icon-24-arrow-right icon-layer"></span>
					</span>
                    Utilisateurs
                </a>
            </li>
        </ul>
    </li>
</ul>

<div id="desktop-navigation" class="desktop-navigation">
    <div class="first-level">
        <ul>
            <li class="home">
                <a title="Accueil" href="/admin/home/index">
					<span class="icon-container">
						<span class="av-icon-24-home"></span>
					</span>
                    <span class="label">Accueil</span>
                </a>
            </li>
            <li class="briiicks">
                <a title="Briiicks" href="#">
					<span class="icon-container">
						<span class="av-icon-24-lego"></span>
					</span>
                    <span class="label">Briiicks</span>
                </a>
            </li>
            <li class="parameters">
                <a href="#" title="Paramètres">
					<span class="icon-container">
						<span class="av-icon-24-equalizer"></span>
					</span>
                    <span class="label">Paramètres</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="second-level briiicks">
        <h3>Briiicks</h3>
        <ul>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/minifigures/listing' || $_GET['page'] == 'admin/minifigures/add' || $_GET['page'] == 'admin/minifigures/edit')) ? 'active' : 'inactive'; ?>" href="/admin/minifigures/listing">
                    <span class="arrow"></span>
                    Figurines
                </a>
            </li>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/sets/listing' || $_GET['page'] == 'admin/sets/add' || $_GET['page'] == 'admin/sets/edit')) ? 'active' : 'inactive'; ?>" href="/admin/sets/listing">
                    <span class="arrow"></span>
                    Sets
                </a>
            </li>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/ranges/listing' || $_GET['page'] == 'admin/ranges/add' || $_GET['page'] == 'admin/ranges/edit')) ? 'active' : 'inactive'; ?>" href="/admin/ranges/listing">
                    <span class="arrow"></span>
                    Gammes
                </a>
            </li>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/series/listing' || $_GET['page'] == 'admin/series/add' || $_GET['page'] == 'admin/series/edit')) ? 'active' : 'inactive'; ?>" href="/admin/series/listing">
                    <span class="arrow"></span>
                    Séries
                </a>
            </li>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/offers/listing' || $_GET['page'] == 'admin/offers/add' || $_GET['page'] == 'admin/offer/edit')) ? 'active' : 'inactive'; ?>" href="/admin/offers/listing">
                    <span class="arrow"></span>
                    Annonces
                </a>
            </li>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/members/listing' || $_GET['page'] == 'admin/members/add' || $_GET['page'] == 'admin/members/edit')) ? 'active' : 'inactive'; ?>" href="/admin/members/listing">
                    <span class="arrow"></span>
                    Membres
                </a>
            </li>
        </ul>
    </div>
    <div class="second-level parameters">
        <h3>Paramètres</h3>
        <ul>
            <li>
                <a class="<?php echo (isset($_GET['page']) && ($_GET['page'] == 'admin/users/listing' || $_GET['page'] == 'admin/users/add' || $_GET['page'] == 'admin/users/edit')) ? 'active' : 'inactive'; ?>" href="/admin/users/listing">
                    <span class="arrow"></span>
                    Utilisateurs
                </a>
            </li>
        </ul>
    </div>
</div>