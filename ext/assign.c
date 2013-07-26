
#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "php_test.h"
#include "test.h"

#include "Zend/zend_operators.h"
#include "Zend/zend_exceptions.h"
#include "Zend/zend_interfaces.h"

#include "kernel/main.h"
#include "kernel/memory.h"


/**
 * Control Flow
 */
ZEPHIR_INIT_CLASS(Test_Assign) {

	ZEPHIR_REGISTER_CLASS(Test, Assign, assign, test_assign_method_entry, 0);


	return SUCCESS;

}

PHP_METHOD(Test_Assign, testAssign1) {

	long a;

	a = 1;

	RETURN_LONG(a);


}

PHP_METHOD(Test_Assign, testAssign2) {

	long a;

	a = 1;

	RETURN_LONG(a);


}

PHP_METHOD(Test_Assign, testAssign3) {

	long a;

	a = 0;

	RETURN_LONG(a);


}

PHP_METHOD(Test_Assign, testAssign4) {

	long a;

	a = 0;

	RETURN_LONG(a);


}

PHP_METHOD(Test_Assign, testAssign5) {

	long a;

	a = (long) (2.0);

	RETURN_LONG(a);


}

PHP_METHOD(Test_Assign, testAssign6) {

	zend_bool a;

	a = (1) ? 1 : 0;

	RETURN_MM_BOOL(a);


}

PHP_METHOD(Test_Assign, testAssign7) {

	zend_bool a;

	a = (1.0) ? 1 : 0;

	RETURN_MM_BOOL(a);


}

PHP_METHOD(Test_Assign, testAssign8) {

	zend_bool a;

	a = 1;

	RETURN_MM_BOOL(a);


}

PHP_METHOD(Test_Assign, testAssign9) {

	zend_bool a;

	a = 0;

	RETURN_MM_BOOL(a);


}

PHP_METHOD(Test_Assign, testAssign10) {

	zend_bool a;

	a = 0;

	RETURN_MM_BOOL(a);


}

PHP_METHOD(Test_Assign, testAssign11) {

	double a;

	a = 0.0;

	RETURN_DOUBLE(a);


}

PHP_METHOD(Test_Assign, testAssign12) {

	double a;

	a = (double) (4);

	RETURN_DOUBLE(a);


}

PHP_METHOD(Test_Assign, testAssign13) {

	double a;

	a = 0;

	RETURN_DOUBLE(a);


}

PHP_METHOD(Test_Assign, testAssign14) {

	double a;

	a = 1;

	RETURN_DOUBLE(a);


}

PHP_METHOD(Test_Assign, testAssign15) {

	double a;

	a = 5.0;

	RETURN_DOUBLE(a);


}

PHP_METHOD(Test_Assign, testAssign16) {

	zval *a;

	ZEPHIR_MM_GROW();
	ZEPHIR_INIT_VAR(a);
	ZVAL_LONG(a, 1);

	RETURN_CCTOR(a);


}

PHP_METHOD(Test_Assign, testAssign17) {

	zval *a;

	ZEPHIR_MM_GROW();
	ZEPHIR_INIT_VAR(a);
	ZVAL_DOUBLE(a, 1.0);

	RETURN_CCTOR(a);


}

PHP_METHOD(Test_Assign, testAssign18) {

	zval *a;

	ZEPHIR_MM_GROW();
	ZEPHIR_INIT_VAR(a);
	ZVAL_BOOL(a, 0);

	RETURN_CCTOR(a);


}

PHP_METHOD(Test_Assign, testAssign19) {

	zval *a;

	ZEPHIR_MM_GROW();
	ZEPHIR_INIT_VAR(a);
	ZVAL_BOOL(a, 1);

	RETURN_CCTOR(a);


}

PHP_METHOD(Test_Assign, testAssign20) {

	zval *a;

	ZEPHIR_MM_GROW();
	ZEPHIR_INIT_VAR(a);
	ZVAL_NULL(a);

	RETURN_CCTOR(a);


}

PHP_METHOD(Test_Assign, testAssign21) {

	long a, b;

	a = 1;

	b = a;

	RETURN_LONG(b);


}

PHP_METHOD(Test_Assign, testAssign22) {

	double a, b;

	a = 1.0;

	b = a;

	RETURN_DOUBLE(b);


}

PHP_METHOD(Test_Assign, testAssign23) {

	zend_bool a, b;

	a = 1;

	b = zephir_get_boolval(a);

	RETURN_MM_BOOL(b);


}

PHP_METHOD(Test_Assign, testAssign24) {

	double b;
	long a;

	a = 1;

	b = (double) a;

	RETURN_DOUBLE(b);


}
