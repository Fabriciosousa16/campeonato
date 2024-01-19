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

  role_id: any;
  showButton = false;

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
    this.showButtonExistSimulation();
  }

  // Mostrar Lista de Partidas entre as equipes que não se enfrentaram ainda
  showRole() {
    // this.SimulationService.showSimulation(this.role_id).subscribe((resp: any) => {
    //   console.log(resp);
    //   // this.name = resp.name;
    // });

    this.SimulationService.listMatchSimulation(this.role_id).subscribe(
      (resp: any) => {
        // Verifique se o array championshipsList existe nos dados retornados
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
  }

  // Mostrar botão para realizar sorteio, caso campeonato esteja apenas no nivel 1 (Aberto)
  showButtonExistSimulation() {
    this.SimulationService.verifyExistSimulation(this.role_id).subscribe(
      (resp: any) => {
        // Adiciona log para verificar se 'quantidade' está presente
        // console.log('Quantidade presente:', resp && resp.quant !== undefined);

        // Verifica se a resposta não é nula e possui a propriedade 'quantidade'
        if (resp && resp.quant !== undefined) {
          // console.log('Quantidade:', resp.quant);

          // Verifica se 'quantidade' é menor ou igual a 0
          this.showButton = resp.quant <= 0;
        } else {
          // console.log('Resposta da API não possui a propriedade "quantidade".');
          // Trate conforme necessário, como definir this.showButton para um valor padrão
        }
      },
      (error) => {
        console.error('Erro ao verificar existência da simulação', error);
      }
    );
  }

  //Realizar o sorteio das equipes (Soteio Inicial)
  drawTeams() {
    this.SimulationService.drawSimulation(this.role_id).subscribe(

      (response: any) => {
        // Lógica para tratar a resposta após o sorteio, se necessário
        console.log('Resultado do sorteio:', response);

        this.reloadPage();

      },
      (error) => {
        console.error('Erro ao realizar sorteio das equipes', error);
        // Pode tratar o erro aqui, se necessário
      }
    );

  }

  reloadPage() {
    this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
      this.router.navigate(['simulation/list/edit/' + this.role_id]);  // Substitua 'sua-rota-aqui' pela rota atual
    });
  }

  // Simular Partida do campeonto
  simulateMatch() {

    console.log('Campeonato ID:', this.campeonato_id);

    this.SimulationService.simulateMatch(this.campeonato_id).subscribe(

      (response: any) => {
        // Lógica para tratar a resposta após o sorteio, se necessário
        console.log('Partida Simulada:', response);

        // Recarregar a página após a simulação
        this.reloadPage();
      },
      (error) => {
        console.error('Erro ao simular Patida', error);
        // Pode tratar o erro aqui, se necessário
      }
    );
  }
}
