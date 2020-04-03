<?php

/*
 * Toolbox
 */

namespace Tests\Fixtures;

/**
 * A factory to create a complex object with aggregation
 */
trait AtomBuilder
{

    protected function createAtom(string $n, int $z, int $w): Atom
    {
        $up = new Quark('up', 2 / 3);
        $down = new Quark('down', -1 / 3);
        $proton = new Hadron('proton', [$up, $up, $down]);
        $neutron = new Hadron('neutron', [$up, $down, $down]);
        $nucleus = new Nucleus(array_merge(array_fill(0, $z, $proton), array_fill($z, $w - $z, $neutron)));
        $electron = new Lepton('electron');
        $atom = new Atom($n, $nucleus, array_fill(0, $z, $electron));

        return $atom;
    }

}
