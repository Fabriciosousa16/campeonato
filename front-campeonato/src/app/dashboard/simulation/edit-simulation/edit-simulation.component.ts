import { SimulationService } from './../../../shared/simulation/simulation.service';
import { Component } from '@angular/core';
import { DataService } from 'src/app/shared/data/data.service';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-edit-simulation',
  templateUrl: './edit-simulation.component.html',
  styleUrls: ['./edit-simulation.component.scss']
})
export class EditSimulationComponent {
  sideBar: any = [];
  name = '';
  valid_form = false;
  valid_form_success = false;
  text_validation: any = null;

  public campeonato_id: any;
  public simulationMatchList: any = [];
  public simulationResultMatchList: any = [];

  role_id: any;
  showButtonMatch = false;
  showButtonPrizeDraw = false;

  constructor(
    public DataService: DataService,
    public SimulationService: SimulationService,
    public activedRoute: ActivatedRoute,
    private router: Router

  ) { }

  ngOnInit() {
    this.sideBar = this.DataService.sideBar[0].menu;
    this.activedRoute.params.subscribe((resp: any) => {
      this.role_id = resp.id;
    });
    this.showRole();
    this.showButtonPrizeDrawExist();
  }

  showRole() {

    this.SimulationService.listMatchSimulation(this.role_id).subscribe(
      (resp: any) => {
        if (resp && Array.isArray(resp.resultado)) {
          this.simulationMatchList = resp.resultado;
        } else {
          console.error('Array simulationMatchList não encontrado nos dados retornados.');
        }
      },
      (error) => {
        console.error('Erro ao obter lista de Partidas:', error);
      }
    );

    this.SimulationService.listResultMatchSimulation(this.role_id).subscribe(
      (resp: any) => {
        if (resp && Array.isArray(resp.result)) {
          this.simulationResultMatchList = resp.result;
        } else {
          console.error('Array simulationResultMatchList não encontrado nos dados retornados.');
        }
      },
      (error) => {
        console.error('Erro ao obter lista de Partidas:', error);
      }

    );

  }

  showButtonPrizeDrawExist() {
    this.SimulationService.verifyExistSimulation(this.role_id).subscribe(
      (resp: any) => {
        if (resp && resp.quant !== undefined) {
          console.log('Quantidade:', resp.quant);

          this.showButtonPrizeDraw = resp.quant <= 0;

          this.showButtonMatch = !this.showButtonPrizeDraw;
        } else {
          console.log('Resposta da API não possui a propriedade "quantidade".');
        }
      },
      (error) => {
        console.error('Erro ao verificar existência da simulação', error);
      }
    );
  }


  drawTeams() {
    this.SimulationService.drawSimulation(this.role_id).subscribe(

      (response: any) => {
        console.log('Resultado do sorteio:', response);

        this.reloadPage();

      },
      (error) => {
        console.error('Erro ao realizar sorteio das equipes', error);
      }
    );

  }

  reloadPage() {
    this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
      this.router.navigate(['simulation/list/edit/' + this.role_id]);  // Substitua 'sua-rota-aqui' pela rota atual
    });
  }

  simulateMatch() {

    console.log('Campeonato ID:', this.campeonato_id);

    this.SimulationService.simulateMatch(this.campeonato_id).subscribe(

      (response: any) => {
        console.log('Partida Simulada:', response);

        this.reloadPage();
      },
      (error) => {
        console.error('Erro ao simular Patida', error);
      }
    );
  }
}
