<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('user.Fullname', 'Client'),
            TextField::new('carrierName', 'Carrier Name'),
            MoneyField::new('carrierPrice', 'livraison')->setCurrency('EUR'),
            MoneyField::new('subTotalHT', 'Sous-Total HT')->setCurrency('EUR'),
            MoneyField::new('subTotalTTC', 'Sous-Total TTC')->setCurrency('EUR'),
            BooleanField::new('isPaid')

            
        ];
    }

}
