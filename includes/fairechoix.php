<?php

echo '<div id="choix_tete">Cliquez sur votre choix</div>
				
				<div id="choix_corps">

					<div id="choix1"><a href="choix.php?ids='.$data['id_chx'].'&chx=1&dts='.$data['date_chx'].'">'.mb_strtoupper($data['chx_un']).'</a></div><div id="choix2"><a href="choix.php?ids='.$data['id_chx'].'&chx=2&dts='.$data['date_chx'].'">'.mb_strtoupper($data['chx_deux']).'</a></div>

				</div>
				
				<div id="choix_pied">?</div>

			</div>

		</div>

	</body>

</html>';