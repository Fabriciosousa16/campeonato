export class routes {

  private static Url = '';

  public static get baseUrl(): string {
    return this.Url;
  }
  public static get login(): string {
    return this.baseUrl + '/login';
  }
  public static get register(): string {
    return this.baseUrl + '/register';
  }
  public static get Dashboard(): string {
    return this.baseUrl + '/dashboard';
  }
  public static get addChampionship(): string {
    return this.baseUrl + '/championship/register';
  }
  public static get editChampionship(): string {
    return this.baseUrl + '/championship/edit';
  }
  public static get listChampionship(): string {
    return this.baseUrl + '/championship/list';
  }
  public static get addTeams(): string {
    return this.baseUrl + '/teams/register';
  }
  public static get editTeams(): string {
    return this.baseUrl + '/teams/edit';
  }
  public static get listTeams(): string {
    return this.baseUrl + '/teams/list';
  }
  public static get editSimulation(): string {
    return this.baseUrl + '/simulation/edit';
  }
  public static get listSimulation(): string {
    return this.baseUrl + '/simulation/list';
  }
  public static get listHistory(): string {
    return this.baseUrl + '/history/list';
  }
  public static get error404(): string {
    return this.baseUrl + '/error/error404';
  }
  public static get error500(): string {
    return this.baseUrl + '/error/error500';
  }

  public static get myChampionship(): string {
    return this.baseUrl + '/my-championship/list';
  }
}
