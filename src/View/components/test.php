<div class="container-fluid">
    <div class="row mt-5 mb-5">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <h1 class="text-primary text-center">Bienvenue sur MyTwitter ! {{ test }}</h1>
            {{ posts }}
            @form class={form-content} action={javascript:void(0)} method={post} id={login}
            @label for={example} value={Email} @endlabel
            @input type={email} class={form-control} name={email} id={login} placeholder={Entrez votre email}
            @input type={submit} class={btn btn-primary} value={Se connecter}
            @endform
            {# if (4 + 4 === 8) #}
            <h1>lol</h1>
            {# endif #}

            {# foreach (tests as post => value) #}
            <h6 class="mt-5 text-center">{echo_var=value}</h6><br>
            <h6 class="mt-5 text-center">{echo_var=post}</h6>
            {# endforeach #}
            <h6 class="mt-5 text-center">Pas encore inscrit ? <a href="/signup">Inscrivez-vous.</a></h6>
            <div class="row d-flex justify-content-between align-items-center" id="alert-row">
                <div class="alert alert-danger mt-3" id="error-message" role="alert">
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>