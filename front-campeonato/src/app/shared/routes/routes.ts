export class routes {

  private static Url = '';

  public static get baseUrl(): string {
    return this.Url;
  }
  public static get changePassword2(): string {
    return this.baseUrl + '/change-password2';
  }
  public static get forgotPassword(): string {
    return this.baseUrl + '/forgot-password';
  }
  public static get lockScreen(): string {
    return this.baseUrl + '/lock-screen';
  }
  public static get login(): string {
    return this.baseUrl + '/login';
  }
  public static get register(): string {
    return this.baseUrl + '/register';
  }
  public static get Dashboard(): string {
    return this.baseUrl + '/dashboard/dashboard';
  }
  public static get addUser(): string {
    return this.baseUrl + '/user/add-user';
  }
  public static get usersList(): string {
    return this.baseUrl + '/user/users-list';
  }
  public static get editUser(): string {
    return this.baseUrl + '/user/edit-user';
  }
  public static get addChampionship(): string {
    return this.baseUrl + '/championship/add-championship';
  }
  public static get editChampionship(): string {
    return this.baseUrl + '/championship/edit-championship';
  }
  public static get championshipsList(): string {
    return this.baseUrl + '/championship/championships-list';
  }
  public static get profile(): string {
    return this.baseUrl + '/profile';
  }
  public static get editProfile(): string {
    return this.baseUrl + '/edit-profile';
  }
  public static get error404(): string {
    return this.baseUrl + '/error/error404';
  }
  public static get error500(): string {
    return this.baseUrl + '/error/error500';
  }

  public static get addRoleChampionship(): string {
    return this.baseUrl + '/roles/register';
  }
  public static get editRoleChampionship(): string {
    return this.baseUrl + '/roles/edit';
  }
  public static get listRoleChampionship(): string {
    return this.baseUrl + '/roles/list';
  }
}
