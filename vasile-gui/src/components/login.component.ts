import {Component} from '@angular/core';

@Component({
    moduleId: 'app-login',
    template: `
        <div class="d-flex flex-row justify-content-center h-100">
            <div class="card align-self-center bg-dark border-secondary" style="width: 300px">
                <div class="card-header">Credentiale</div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <input type="text" class="form-control bg-dark text-light border-secondary" placeholder="nume utilizator">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control bg-dark text-light border-secondary" placeholder="parola">
                        </div>
                    </form>
                </div>
                <div class="card-footer d-flex justify-content-center">
                    <button class="btn btn-sm btn-outline-success">Autentifica</button>
                </div>
            </div>
        </div>
    `
})
export class LoginComponent {

}
