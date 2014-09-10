<?php

namespace CP\Terms\DemoBundle\Controller;

use Criteria;

use CP\Terms\DemoBundle\Form\Type\UsersFilterType;

use FOS\UserBundle\Propel\UserQuery;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/user")
 */
class UserAdminController extends Controller
{
    /**
     * @Route("/", name="admin_users")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(new UsersFilterType());

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/list", name="admin_users_list", requirements={"_format": "json"}, defaults={"_format": "json"})
     * @Template()
     */
    public function listAction(Request $request)
    {
        $query = $this->getQuery();

        $total_count = $query->count();

        $this->search(
            $query,
            $this->getFilters($request)
        );

        $filtered_count = $query->count();

        $this->sort($query, $request->query);

        $limit = $this->getLimit($request);
        $offset = $this->getOffset($request);

        $users = $query
            ->setFormatter('PropelOnDemandFormatter')
            ->setLimit($limit)
            ->setOffset($offset)
            ->find();

        return array(
            'total_count' => $total_count,
            'filtered_count' => $filtered_count,
            'users' => $users
        );
    }

    /**
     * @Route("/{id}", name="admin_user_info", requirements={"id" = "\d+"})
     * @Template()
     */
    public function infoAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirect($this->generateUrl('admin_users'));
        }

        $user = $this->getQuery()
            ->findPk($id);

        return array(
            'user' => $user
        );
    }

    protected function getQuery()
    {
        return UserQuery::create()
            ->leftJoinAgreement()
            ->distinct();
    }

    protected function getLimit(Request $request)
    {
        return min(100, $request->query->get('length', 10));
    }

    protected function getOffset(Request $request)
    {
        return max($request->query->get('start', 0), 0);
    }

    protected function search(UserQuery $query, $filters = array())
    {
        if (empty($filters)) {
            return $query;
        }

        $conditions = array();

        $columns = $this->getSearchColumns();

        foreach ($columns as $name => $condition) {
            if (!array_key_exists($name, $filters)) {
                continue;
            }

            $value = trim($filters[$name]);

            if (empty($value) && !is_numeric($value)) {
                continue;
            }

            if ($name == 'tos' && $value == 'null') {
                $condition = str_replace('= %d', 'is %s', $condition);
            }

            $query->condition(
                'search_' . $name,
                sprintf($condition, $value)
            );

            $conditions[] = 'search_' . $name;
        }

        if (!empty($conditions)) {
            return $query->where($conditions, 'and');
        }
    }

    protected function getFilters(Request $request)
    {
        return $request->query->get('users_filters', array());
    }

    protected function getSearchColumns()
    {
        return array(
            'username' => 'fos_user.username LIKE "%%%s%%"',
            'tos' => 'Agreement.termsId = %d'
        );
    }

    protected function sort(UserQuery $query, ParameterBag $params)
    {
        $order = $params->get('order');

        $columns = $this->getSortColumns();

        $control = 0;

        foreach ($order as $settings) {

            $index = $settings['column'];

            if (array_key_exists($index, $columns)) {
                $sort_columns = $columns[$index];

                $dir = $settings['dir'] == 'asc'
                    ? Criteria::ASC
                    : Criteria::DESC;

                if (!is_array($sort_columns)) {
                    $sort_columns = array($sort_columns);
                }

                foreach ($sort_columns as $column) {
                    $query->orderBy($column, $dir);
                }

                $control++;
            }
        }

        if ($control == 0) {
            $query = $this->defaultSort($query);
        }

        return $query;
    }

    protected function getSortColumns()
    {
        return array(
            1 => 'fos_user.username',
            2 => 'fos_user.email',
            3 => 'fos_user.last_login'
        );
    }

    protected function defaultSort(UserQuery $query)
    {
        return $query->orderByUsername();
    }
}
