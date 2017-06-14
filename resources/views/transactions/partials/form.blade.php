@inject('fieldValues', 'App\Services\FormFieldOptionService')

@push('footer')
<script type="text/javascript">
    $(document).ready(function () {
        autosize($('textarea'));

        $("[type=date-local]").each((index, elm) => {
            new kendo.ui.DateInput($(elm), {
                value: new Date()
            });
        });
        $(".dropdown-list").each((index, elm) => {
            new kendo.ui.DropDownList($(elm), {
                filter: "startswith",
            });
        });

        $("#category").each((index, elm) => {
            new kendo.ui.DropDownList($(elm), {
                filter: "startswith",
            });
        });

        $("#subcategory").each((index, elm) => {
            new kendo.ui.DropDownList($(elm), {
                autoBind: false,
                cascadeFrom: "category",
                filter: "startswith",
            });
        });


        $(".numeric-currency").each((index, elm) => {
            new kendo.ui.NumericTextBox($(elm), {
                format: "c",
                decimals: 2
            });
        });
    });
</script>
@endpush

<div class="form-group label-static is-empty">
    <label for="transaction_date" class="control-label">@lang('Date')</label>
    <input type="date-local" name="transaction_date" placeholder="Von"
           value="{{old('date_from')}}">

    <span class="help-block">Gib an, von wann bis wann das Event stattfindet</span>
</div>

<div class="form-group label-static is-empty">
    <label for="transaction_status" class="control-label">@lang('Status')</label>
    <select name="transaction_status" class="dropdown-list">
        @foreach($fieldValues->getValues(App\Models\TransactionStatus::class) as $value)
            <option value="{{$value->id}}">{{$value->name}}</option>
        @endforeach
    </select>

    <span class="help-block">Gib an, von wann bis wann das Event stattfindet</span>
</div>
<div class="form-group label-static is-empty">
    <label for="transaction_type" class="control-label">@lang('Type')</label>
    <select name="transaction_type" class="dropdown-list">
        <option value="Zahlung"> Zahlung</option>
        <option value="Gutschrift"> Gutschrift</option>
        <option value="Übertrag"> Übertrag</option>
    </select>

    <span class="help-block">Gib an, von wann bis wann das Event stattfindet</span>
</div>

<div class="form-group label-static is-empty">
    <label for="account" class="control-label">@lang('Account')</label>
    <select name="account" class="dropdown-list">
        <option value="Bargeld"> Bargeld</option>
        <option value="Jugendkonto (Lohn)"> Jugendkonto (Lohn)</option>
        <option value="Kreditkarte"> Kreditkarte</option>
        <option value="Postkonto"> Postkonto</option>
        <option value="Rechnungs und Steuerkonto"> Rechnungs und Steuerkonto</option>
        <option value="Schuldenkonto"> Schuldenkonto</option>
        <option value="Sparkonto"> Sparkonto</option>
    </select>

    <span class="help-block">Gib an, von wann bis wann das Event stattfindet</span>
</div>

<div class="form-group label-static is-empty">
    <label for="to_account" class="control-label">@lang('to Account')</label>
    <select name="to_account" class="dropdown-list">
        <option value="Bargeld"> Bargeld</option>
        <option value="Jugendkonto (Lohn)"> Jugendkonto (Lohn)</option>
        <option value="Kreditkarte"> Kreditkarte</option>
        <option value="Postkonto"> Postkonto</option>
        <option value="Rechnungs und Steuerkonto"> Rechnungs und Steuerkonto</option>
        <option value="Schuldenkonto"> Schuldenkonto</option>
        <option value="Sparkonto"> Sparkonto</option>
    </select>

    <span class="help-block">Gib an, von wann bis wann das Event stattfindet</span>
</div>

<div class="form-group label-static is-empty">
    <label for="payee" class="control-label">@lang('Payee')</label>
    <select name="payee" class="dropdown-list">
        <option value="Keine Auswahl">Keine Auswahl</option>
        <option value="3 Tells">3 Tells</option>
        <option value="AliExpress">AliExpress</option>
        <option value="Apaloosa, Spiez">Apaloosa, Spiez</option>
        <option value="Apotheke">Apotheke</option>
        <option value="Arirang">Arirang</option>
        <option value="Ausgang">Ausgang</option>
        <option value="Azzurro Terra e Mare, Bern">Azzurro Terra e Mare, Bern</option>
        <option value="Bigote verde">Bigote verde</option>
        <option value="Billag">Billag</option>
        <option value="Brasserie 17">Brasserie 17</option>
        <option value="Bäckerei, Spiez Bahnhof">Bäckerei, Spiez Bahnhof</option>
        <option value="Bären Köniz">Bären Köniz</option>
        <option value="ChaCha">ChaCha</option>
        <option value="Chili Pizza">Chili Pizza</option>
        <option value="Christian Blättler, Rocketstart">Christian Blättler, Rocketstart
        </option>
        <option value="Coop">Coop</option>
        <option value="CrestaProject">CrestaProject</option>
        <option value="Cyon">Cyon</option>
        <option value="Denner">Denner</option>
        <option value="dieci">dieci</option>
        <option value="Digitec">Digitec</option>
        <option value="Emil von Wattenwyl">Emil von Wattenwyl</option>
        <option value="Familie Werner">Familie Werner</option>
        <option value="Franziska Werner">Franziska Werner</option>
        <option value="Garaio AG">Garaio AG</option>
        <option value="Gemeindeverwaltung Habkern">Gemeindeverwaltung Habkern</option>
        <option value="Gibb">Gibb</option>
        <option value="Highlife">Highlife</option>
        <option value="India4u">India4u</option>
        <option value="Industrielle Betriebe Interlaken">Industrielle Betriebe Interlaken
        </option>
        <option value="Kabelfehrnsen Bödeli">Kabelfehrnsen Bödeli</option>
        <option value="Kickstarter">Kickstarter</option>
        <option value="Kino Rex">Kino Rex</option>
        <option value="Landi Jungfrau AG">Landi Jungfrau AG</option>
        <option value="Laracasts">Laracasts</option>
        <option value="Manuel Sohm">Manuel Sohm</option>
        <option value="Matthias Ossola">Matthias Ossola</option>
        <option value="Mekong">Mekong</option>
        <option value="Michel Ramseier">Michel Ramseier</option>
        <option value="Microspot">Microspot</option>
        <option value="Migros">Migros</option>
        <option value="Monika Kaufmann">Monika Kaufmann</option>
        <option value="Natthakit Khamso">Natthakit Khamso</option>
        <option value="Netflix">Netflix</option>
        <option value="Orient Express">Orient Express</option>
        <option value="Pangäa">Pangäa</option>
        <option value="Papeterie Schaffner">Papeterie Schaffner</option>
        <option value="Pho Saigon, Thai, Köniz">Pho Saigon, Thai, Köniz</option>
        <option value="Pizza Eigerplatz">Pizza Eigerplatz</option>
        <option value="Post Finance">Post Finance</option>
        <option value="Raiffeisenbank Jungfrau">Raiffeisenbank Jungfrau</option>
        <option value="Rugenbräu AG">Rugenbräu AG</option>
        <option value="Sam's Pizza Land">Sam's Pizza Land</option>
        <option value="SBB">SBB</option>
        <option value="Schweizerische Mobiliar">Schweizerische Mobiliar</option>
        <option value="Simon Schmid">Simon Schmid</option>
        <option value="Spar">Spar</option>
        <option value="Spotify">Spotify</option>
        <option value="Steam">Steam</option>
        <option value="Steuerverwaltung des Kantons Bern">Steuerverwaltung des Kantons
            Bern
        </option>
        <option value="Strassenverkehrs- und Schifffahrtsamt">Strassenverkehrs- und
            Schifffahrtsamt
        </option>
        <option value="Swica">Swica</option>
        <option value="Swisscom">Swisscom</option>
        <option value="Swisstoy">Swisstoy</option>
        <option value="Thai Food Kurier">Thai Food Kurier</option>
        <option value="THE BARREL, Interlaken">THE BARREL, Interlaken</option>
        <option value="The Beef Burgers, Bern">The Beef Burgers, Bern</option>
        <option value="The Beef, Welle 7">The Beef, Welle 7</option>
        <option value="Thomas Burkhard">Thomas Burkhard</option>
        <option value="Tien Tien, Monbijou, Bern">Tien Tien, Monbijou, Bern</option>
        <option value="Toni Kaufmann">Toni Kaufmann</option>
        <option value="Unbekannt">Unbekannt</option>
        <option value="Waldeggkeller">Waldeggkeller</option>
        <option value="Wog.ch">Wog.ch</option>
        <option value="Yannick Häsler">Yannick Häsler</option>
    </select>

    <span class="help-block">Gib an, von wann bis wann das Event stattfindet</span>
</div>

<div class="form-group label-static is-empty">
    <label for="category" class="control-label">@lang('Category')</label>
    <select id="category" name="category">
        <option value="Keine Auswahl">Keine Auswahl</option>
        <option value="andere Ausgaben">andere Ausgaben</option>
        <option value="andere Einnahmen">andere Einnahmen</option>
        <option value="Ausbildung">Ausbildung</option>
        <option value="EDV">EDV</option>
        <option value="Einnahmen">Einnahmen</option>
        <option value="Freizeit">Freizeit</option>
        <option value="Gebühren und Zinsen">Gebühren und Zinsen</option>
        <option value="Geschenke">Geschenke</option>
        <option value="Gesundheit">Gesundheit</option>
        <option value="Heim- Hausbedarf">Heim- Hausbedarf</option>
        <option value="Kredite">Kredite</option>
        <option value="Lebensmittel">Lebensmittel</option>
        <option value="Pkw">Pkw</option>
        <option value="Rechnungen">Rechnungen</option>
        <option value="Steuern">Steuern</option>
        <option value="Transfer">Transfer</option>
        <option value="Transport">Transport</option>
        <option value="Urlaub">Urlaub</option>
        <option value="Verschiedenes">Verschiedenes</option>
        <option value="Versicherung">Versicherung</option>
        <option value="Bargeld"> Bargeld</option>
        <option value="Jugendkonto (Lohn)"> Jugendkonto (Lohn)</option>
        <option value="Kreditkarte"> Kreditkarte</option>
        <option value="Postkonto"> Postkonto</option>
        <option value="Rechnungs und Steuerkonto"> Rechnungs und Steuerkonto</option>
        <option value="Schuldenkonto"> Schuldenkonto</option>
        <option value="Sparkonto"> Sparkonto</option>
    </select>

    <span class="help-block">Gib an, von wann bis wann das Event stattfindet</span>
</div>
<div class="form-group label-static is-empty">
    <label for="subcategory" class="control-label">@lang('Subcategory')</label>
    <select id="subcategory" name="subcategory"></select>

    <span class="help-block">Gib an, von wann bis wann das Event stattfindet</span>
</div>
<div class="form-group label-static is-empty">
    <label for="amount" class="control-label">@lang('Amount')</label>
    <input name="amount" type="number" title="currency" value="{{old('amount')}}" min="0"
           class="numeric-currency"/>

    <span class="help-block">Gib an, von wann bis wann das Event stattfindet</span>
</div>

<div class="form-group label-static is-empty">
    <label for="notes" class="control-label">@lang('Notes')</label>
    <textarea name="notes" class="form-control" rows="5"
              placeholder="@lang('New transaction notes')"></textarea>

    <span class="help-block">Gib an, von wann bis wann das Event stattfindet</span>
</div>
<div class="form-group label-static is-empty">
    <label for="inputFile" class="control-label">@lang('Take a picture or upload attachments')</label>

    <input type="text" readonly="" class="form-control" placeholder="Browse...">
    <input type="file" multiple="">
</div>