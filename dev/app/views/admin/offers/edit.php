<div id="content" class="content">
    <div class="col-pd">
        <div class="col-xs-12 col-pd">
            <h1>Modifier l'annonce</h1>
            <?php if(array_key_exists('success', $_SESSION)) { ?>
                <p class="success">
                    <?php echo $_SESSION['success']; ?>
                </p>
            <?php } elseif(array_key_exists('error', $_SESSION)) { ?>
                <p class="error">
                    <?php echo $_SESSION['error']; ?>
                </p>
            <?php } ?>
        </div>
        <form action="/admin/offers/edit?id=<?php if(isset($_GET['id'])) echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id" value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>">
            <div class="font-size-zero">
                <div class="col-xs-12 col-pd font-size-default">
                    <p class="author">Auteur : Aurélien Vattant</p>
                    <label>
                        Type d'annonce
                    </label>
                    <input type="checkbox" id="sale_type" name="type" value="vente">
                    <label for="sale_type">Vente</label>
                    <input type="checkbox" id="exchange_type" name="type" value="echange">
                    <label for="exchange_type">Echange</label>
                </div>
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="title">Titre de l'annonce</label>
                    <input type="text" id="title" name="title" value="Minifigures diverses - état neuf">
                    <label for="price">Prix</label>
                    <input type="text" id="price" name="price" value="55 €">
                </div>
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="sets">
                        Sélection des sets
                    </label>
                    <select id="sets" name="sets">
                        <option>7473 - X-Wing Starfighter</option>
                        <option>9492 - Tie Fighter</option>
                        <option>75019 - AT-TE</option>
                    </select>
                    <p>Aucun set sélectionné</p>
                    <label for="minifigures">
                        Sélection des figurines
                    </label>
                    <select id="minifigures" name="minifigures">
                        <option>Le barbare</option>
                        <option>L'épouvantail</option>
                        <option>La fille bretzel</option>
                    </select>
                    <div class="item-box">
                        <div class="item">
                            <div class="action">
                                <span class="close"></span>
                            </div>
                            <img src="/assets/img/minifigures/57x57/50-le-barbare.jpg">
                            <p><span class="name">Luke skywalker</span><br>Star Wars - Trilogie</p>
                        </div>
                        <div class="number">
                            <select name="number">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                            </select>
                            <label for="number">Nombre d'exemplaires</label>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-s-6 col-m-4 col-pd font-size-default">
                    <label for="photos">
                        Photos
                    </label>
                    <input type="file" id="photos" name="photos">
                    <a href="#"><span class="plus"></span>Ajouter une autre photo</a>
                </div>
                <div class="col-xs-12 col-m-8 col-pd font-size-default">
                    <label for="text">Texte de l'annonce</label>
                    <textarea id="text" name="text"></textarea>
                </div>
            </div>
            <div class="col-xs-12 col-m-8 col-pd">
                <p class="caption">* Champs obligatoires</p>
                <div class="buttons">
                    <a href="/admin/offers/listing" tabindex="6"><span class="cancel"></span>Annuler</a>
                    <button type="submit" class="btn-1-m" tabindex="7">
                        <span class="check"></span>
                        Valider
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>