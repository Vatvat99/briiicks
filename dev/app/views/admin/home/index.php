<div id="content" class="content">
    <div class="minifigs col-pd">
        <div class=" col-xs-12 col-pd">
            <h1>Briiicks</h1>
            <p class="description">Ajout / suppression de figurines, sets et membres. Consultation des annonces présentes sur le site.</p>
            <ul class="pages">
                <li>
                    <a href="/admin/minifigures/listing">Figurines</a>
                </li>
                <li>
                    <a href="/admin/sets/listing">Sets</a>
                </li>
                <li>
                    <a href="/admin/ranges/listing">Gammes</a>
                </li>
                <li>
                    <a href="/admin/series/listing">Séries</a>
                </li>
                <li>
                    <a href="/admin/offers/listing">Annonces</a>
                </li>
                <li>
                    <a href="/admin/members/listing">Membres</a>
                </li>
            </ul>
            <div class="font-size-zero">
                <div class="col-xs-12 col-s-4 col-s-pd center font-size-default">
                    <p class="minifigs">
                        <span class="number"><?php echo $minifigures_number; ?></span> figs
                        <br>
                        <a href="/admin/minifigures/listing-validation">
                            <span class="validation-number">0</span> en attente de validation
                        </a>
                    </p>
                </div>
                <div class="col-xs-12 col-s-4 col-s-pd center font-size-default">
                    <p class="sets">
                        <span class="number"><?php echo $sets_number; ?></span> sets
                        <br>
                        <a href="#">
                            <span class="validation-number">0</span> en attente de validation
                        </a>
                    </p>
                </div>
                <div class="others col-xs-12 col-s-4 col-s-pd center font-size-zero">
                    <p class="other col-xs-6 col-s-12 font-size-default">
                        <span class="number"><?php echo $members_number; ?></span> membres
                    </p>
                    <p class="other col-xs-6 col-s-12 font-size-default">
                        <span class="number">0</span> annonces
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-pd font-size-zero">
        <div class="rubric col-xs-12 col-s-4 col-pd font-size-default">
            <h2>
                <span class="icone parameters"></span>
                Paramètres
            </h2>
            <p class="description">
                Gestion des paramètres de l'administration
            </p>
            <ul class="pages">
                <li>
                    <a href="/admin/users/listing">
                        Utilisateurs
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>