<?php

require_once __DIR__.'/../vendor/autoload.php';

// $client = \Hprose\Client::create('http://localhost:9001/server.php', false);

// print_r($client->name('222').PHP_EOL);
// print_r($client->tests->ccc->aaa('asfasfasfasf').PHP_EOL);
// // print_r($client->bbb('asfasfasfasf').PHP_EOL);
// // print_r($client->a->b->c->d->e->f->g->h->i->j->v->k->l->m->n->o->p->q->r->s->t->u->v->w->x->y->z('111').PHP_EOL);

// // $client->name('asfasfasfasf')->then(function ($result) {
// //     var_dump($result);
// // });

$app = array(
    'config' => array(
        'hprose.client.default'     => 'socket',
        'hprose.client.connections' => array(
            'http' => array(
                'protocol' => 'http',
                'uri'      => 'http://localhost/server.php',
                'async'    => false,
            ),

            'socket' => array(
                'protocol' => 'socket',
                // 'uri'      => 'http://localhost:9001/server.php',
                'uri'      => 'tcp://0.0.0.0:1314',
                'async'    => false,
            ),
        ),
    ),
);

$client = new \Flc\Laravel\Hprose\Client($app);

// for ($i = 1; $i <= 10000; $i ++) {
    print_r($client->name('222').PHP_EOL);
// }

