import * as $ from 'jquery';

import { Component, OnInit, QueryList, ViewChildren } from '@angular/core';
import { SearchService } from '../../services/search.service';

@Component({
  selector: 'app-result',
  templateUrl: './result.component.html',
  styleUrls: ['./result.component.css']
})

export class ResultComponent implements OnInit
{
	public data: any = null;

	public relation_type_selected: any = null;

	public definition_page_size: number = 5;
	public definition_page_current : number = 1;

	public raffs: any = {
		"lion>félin": [
            "Mâle de cette espèce.\n",
            "Le lion (Panthera leo) est une espèce de mammifères carnivores de la famille des félidés. La femelle du lion est la lionne, son petit est le lionceau. Le mâle adulte, aisément reconnaissable à son importante crinière, accuse une masse moyenne qui peut être variable selon les zones géographiques où il se trouve, allant de 180 kg pour les lions de Kruger à 230 kg pour les lions de Transvaal. Certains spécimens très rares peuvent dépasser exceptionnellement 250 kg. Un mâle adulte se nourrit de 7 kg de viande chaque jour contre 5 kg chez la femelle. Le lion est un animal grégaire, c'est-à-dire qu'il vit en larges groupes familiaux, contrairement aux autres félins. Son espérance de vie, à l'état sauvage, est comprise entre 7 et 12 ans pour le mâle et 14 à 20 ans pour la femelle, mais il dépasse fréquemment les 30 ans en captivité.\n",
            "(Zoologie) Grand félin carnivore, Panthera leo sur Wikispecies , autrefois répandu des Balkans au sous-continent indien et dans toute l'Afrique. \n Le lion a complètement disparu de la région dont nous nous occupons; mais il joue un grand rôle dans les contes indigènes, qui le représentent comme un animal essentiellement noble et magnanime.    \n (Frédéric Weisgerber, Trois mois de campagne au Maroc : étude géographique de la région parcourue, Paris : Ernest Leroux, 1904, p. 224)   \n [?] ; le singe, le tigre, le lion éloignés de leur patrie et enfermés dans nos ménageries ne tardent pas à tomber dans un état de décrépitude complet et sont presque toujours enlevés par la phthisie.    \n (Jean Déhès, Essai sur l'amélioration des races chevalines de la France, École impériale vétérinaire de Toulouse, Thèse de médecine vétérinaire, 1868)"
        ],
        "lion>héraldique": [
            "(Héraldique) Figure d'héraldique représentant un félin stylisé, la tête de profil. \n [?]; elle ne signait pas en vain Nieuport-la-Noble ; elle ne portait pas pour rien sur son blason un lion lampassé issant d'une nef et brandissant une hallebarde.    \n (Charles Le Goffic, Bourguignottes et pompons rouges, 1916, p.103)  \n",
            "Le lion est un animal très présent en héraldique, au point qu'un proverbe très répandu, attesté dès le XIIe siècle, affirmait : « qui n'a pas d'armes porte un lion ». Au Moyen Âge, le lion est considéré comme le roi des animaux terrestres, mais sans autorité sur les oiseaux. C'est cet antagonisme entre l'aigle, « seigneure » (l'aigle héraldique est féminin) des cieux et symbole du pouvoir impérial, et le lion qui va motiver le choix de faire figurer cet animal sur des armoiries. On en trouve de nombreux exemples notamment dans les terres d'Empire.\n",
            "En héraldique, le lion et le léopard désignent le même animal, mais avec une position de tête différente."
        ],
        "lion>Lions Clubs": [
            "Membre du Lions Clubs, d'un Lions club."
        ],
        "lion>ride": [
            "(Cosmétologie) Ride du lion, couple de rides formé entre les sourcils. \n Dès 25 ans, les premières rides apparaissent et on ne peut s'empêcher de fixer la naissance de la ride du lion ou du front dans le miroir.    \n (Estelle Abéjean, Antirides : combattre la ride du lion et les rides du front, Cosmopolitan, 2012.)"
        ]
	}

	public raffs_keys: any = Object.keys(this.raffs);
	public colored:boolean = true;

	@ViewChildren('relation_types_view') relation_types_view: QueryList<any>;

	constructor(public search_service : SearchService)
	{
	}

	ngOnInit()
	{
		function callback()
		{
			this.data = this.search_service.getData();
			this.relation_types_view.changes.subscribe(this.showFirstRelationType.bind(this));
		}

		this.search_service.run(callback.bind(this));
	}

	showFirstRelationType(): void
	{
		if (this.relation_type_selected !== null || this.data.relation_types.length === 0)
		{
			return;
		}

		this.showRelations(this.data.relation_types[0], false);
	}
	
	public showRelations(relation_type: any, is_scroll: boolean = true): void
	{
		if (this.relation_type_selected !== null)
		{
			var old_button = $("#button_rt_" + this.relation_type_selected.id);
			var old_content = $("#content_rt_" + this.relation_type_selected.id);

			old_button.addClass('btn-primary');
			old_button.removeClass('btn-outline-primary');
			old_content.addClass('d-none');
		}

		this.relation_type_selected = relation_type;

		var new_button = $("#button_rt_" + this.relation_type_selected.id);
		var new_content = $("#content_rt_" + this.relation_type_selected.id);

		new_button.removeClass('btn-primary');
		new_button.addClass('btn-outline-primary');
		new_content.removeClass('d-none');

		if (is_scroll)
		{
			$("html, body").prop('scrollTop', new_content.offset().top);
		}
	}

	scrollDefinitions(): void
	{
		var current_scroll = $("html, body").prop('scrollTop');
		var def_offset = $('#accordion-definition').offset().top;
		
		if (current_scroll < def_offset)
		{
			return;
		}
		
		$("html, body").prop('scrollTop', def_offset);
	}
}
