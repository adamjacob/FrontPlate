<?php
    class DB
    {
        protected static $instance = null;

        protected static $databaseFile = 'database.sqlite3';

        final private function __clone() {}

        /**
         * @return PDO
         */
        public static function instance() {
            if (self::$instance === null) {
                try {
                    
                    self::$databaseFile = str_replace('classes', '', __DIR__) . self::$databaseFile;

                    self::$instance = new PDO('sqlite:' . self::$databaseFile);
                    self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die('<div style="margin:25% auto;text-align:center;font-family:sans-serif">Database connection could not be established.</div>');
                }
            }

            return self::$instance;
        }

        /**
         * @return PDOStatement
         */
        public static function q($query) {
            if (func_num_args() == 1) {
                return self::instance()->query($query);
            }

            $args = func_get_args();
            return self::instance()->query(self::autoQuote(array_shift($args), $args));
        }

        public static function x($query) {
            if (func_num_args() == 1) {
                return self::instance()->exec($query);
            }

            $args = func_get_args();
            return self::instance()->exec(self::autoQuote(array_shift($args), $args));
        }

        public static function autoQuote($query, array $args) {
            $i = strlen($query) - 1;
            $c = count($args);

            while ($i--) {
                if ('?' === $query[$i] && false !== $type = strpos('sia', $query[$i + 1])) {
                    if (--$c < 0) {
                        throw new InvalidArgumentException('Too little parameters.');
                    }

                    if (0 === $type) {
                        $replace = self::instance()->quote($args[$c]);
                    } elseif (1 === $type) {
                        $replace = intval($args[$c]);
                    } elseif (2 === $type) {
                        foreach ($args[$c] as &$value) {
                            $value = self::instance()->quote($value);
                        }
                        $replace = '(' . implode(',', $args[$c]) . ')';
                    }

                    $query = substr_replace($query, $replace, $i, 2);
                }
            }

            if ($c > 0) {
                throw new InvalidArgumentException('Too many parameters.');
            }

            return $query;
        }

        public static function __callStatic($method, $args) {
            return call_user_func_array(array(self::instance(), $method), $args);
        }
    }