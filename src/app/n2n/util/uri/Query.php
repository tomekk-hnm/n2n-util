<?php
/*
 * Copyright (c) 2012-2016, Hofmänner New Media.
 * DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS FILE HEADER.
 *
 * This file is part of the N2N FRAMEWORK.
 *
 * The N2N FRAMEWORK is free software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * N2N is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details: http://www.gnu.org/licenses/
 *
 * The following people participated in this project:
 *
 * Andreas von Burg.....: Architect, Lead Developer
 * Bert Hofmänner.......: Idea, Community Leader, Marketing
 * Thomas Günther.......: Developer, Hangar
 */
namespace n2n\util\uri;

use n2n\util\StringUtils;
class Query {
	private $attrs = array();
	
	public function __construct(array $attrs) {
		$this->attrs = $this->normalizeAttrs($attrs);
	}
	
	private function normalizeAttrs($attrs) {
		foreach ($attrs as $key => $value) {
			if ($value === null || is_scalar($value)) continue;
				
			try {
				if (is_array($value)) {
					$attrs[$key] = $this->normalizeAttrs($value);
					continue;
				}
				
				$attrs[$key] = StringUtils::strOf($value);
			} catch (\InvalidArgumentException $e) {
				throw new \InvalidArgumentException('Invalid query field: ' . $key);
			}
		}
		
		return $attrs;
	}
	
	public function isEmpty() {
		return 0 == count($this->attrs);
	}
	
	public function contains($name) {
		return array_key_exists($name, $this->attrs);
	}
	
	public function get($name) {
		if ($this->contains($name)) {
			return $this->attrs[$name];
		}
		return null;
	}
	
	
	/**
	 * 
	 * @param mixed $query array or string 
	 * @return \n2n\util\uri\Query
	 */
	public function ext($query) {
		$query = Query::create($query);
		
		return new Query($query->toArray() + $this->attrs);
	}
	
	public function toArray() {
		return $this->attrs;
	}
	
	public function __toString(): string {
		return http_build_query($this->attrs);
		
// 		$strs = array();
// 		foreach ($this->attrs as $name => $value) {
// 			$strs = array_merge($strs, $this->buildArrayStrs($value, $name, array()));
// 		}
// 		return implode('&', $strs);
	}
	
	private function buildArrayStrs($value, $name, array $keys) {
// 		if (!is_array($value)) {
// 			return array($this->buildStr($name, $keys, $value));
// 		}
		
// 		$strs = array();
// 		foreach ($value as $key => $fieldValue) {
// 			$newKeys = $keys;
// 			$newKeys[] = $key;
			
// 			$strs = array_merge($strs, $this->buildArrayStrs($fieldValue, $name, $newKeys));
// 		}
		
// 		return $strs;
	}
	
// 	private function buildStr($name, array $keys, $value) {
// 		$str = urlencode($name);
		
// 		foreach ($keys as $key) {
// 			$str .= '[' . urlencode($key) . ']';
// 		}
		
// 		return $str . '=' . urlencode($value);
// 	}

	public static function create($expression) {
		if ($expression === null) {
			return new Query(array());
		}
		
		if ($expression instanceof Query) {
			return $expression;
		}
	
		if (is_array($expression)) {
			return new Query($expression);
		}
	
		$attrs = null;
		parse_str($expression, $attrs);
		return new Query($attrs);
	}
	
}
